Database Design — Concert Ticket Booking Platform
10 bảng chính và lý do thiết kế

users — Khách hàng
sqlCREATE TABLE users (
    id            UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name          VARCHAR(255) NOT NULL,
    email         VARCHAR(255) NOT NULL,
    phone         VARCHAR(20),
    password_hash VARCHAR(255) NOT NULL,
    email_verified_at TIMESTAMP NULL,
    created_at    TIMESTAMP DEFAULT NOW(),
    updated_at    TIMESTAMP DEFAULT NOW(),

    CONSTRAINT users_email_unique UNIQUE (email)
);

-- Indexes
CREATE INDEX idx_users_email ON users (email);        -- login lookup
CREATE INDEX idx_users_phone ON users (phone);        -- support lookup
CREATE INDEX idx_users_created_at ON users (created_at DESC); -- admin list

concerts — Buổi hòa nhạc
sqlCREATE TABLE concerts (
    id          UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    title       VARCHAR(255) NOT NULL,
    slug        VARCHAR(255) NOT NULL,         -- SEO-friendly URL
    description TEXT,
    venue       VARCHAR(255) NOT NULL,
    city        VARCHAR(100) NOT NULL,
    event_at    TIMESTAMP NOT NULL,
    poster_url  VARCHAR(500),
    status      ENUM('draft','published','cancelled','completed') DEFAULT 'draft',
    created_by  UUID NOT NULL REFERENCES operators(id),
    created_at  TIMESTAMP DEFAULT NOW(),
    updated_at  TIMESTAMP DEFAULT NOW(),

    CONSTRAINT concerts_slug_unique UNIQUE (slug)
);

CREATE INDEX idx_concerts_status        ON concerts (status);
CREATE INDEX idx_concerts_event_at      ON concerts (event_at);
CREATE INDEX idx_concerts_status_event  ON concerts (status, event_at DESC); -- homepage listing

ticket_categories — Loại vé (VIP, Standard, ...)
sqlCREATE TABLE ticket_categories (
    id                UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    concert_id        UUID NOT NULL REFERENCES concerts(id) ON DELETE CASCADE,
    name              VARCHAR(100) NOT NULL,        -- 'VIP', 'Standard'
    description       TEXT,
    price             DECIMAL(12, 2) NOT NULL CHECK (price >= 0),
    total_quantity    INT NOT NULL CHECK (total_quantity > 0),
    available_quantity INT NOT NULL CHECK (available_quantity >= 0),
    max_per_order     INT NOT NULL DEFAULT 4,
    is_active         BOOLEAN DEFAULT TRUE,
    created_at        TIMESTAMP DEFAULT NOW(),
    updated_at        TIMESTAMP DEFAULT NOW(),

    CONSTRAINT chk_available_lte_total CHECK (available_quantity <= total_quantity)
);

CREATE INDEX idx_ticket_cat_concert     ON ticket_categories (concert_id);
CREATE INDEX idx_ticket_cat_active      ON ticket_categories (concert_id, is_active);

Chống overselling: available_quantity được giảm bằng UPDATE ... WHERE available_quantity >= quantity trong một transaction. Không bao giờ dùng read-then-write.


vouchers — Mã giảm giá
sqlCREATE TABLE vouchers (
    id               UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    code             VARCHAR(50) NOT NULL,
    discount_type    ENUM('percentage', 'fixed_amount') NOT NULL,
    discount_value   DECIMAL(12, 2) NOT NULL CHECK (discount_value > 0),
    min_order_value  DECIMAL(12, 2) DEFAULT 0,
    total_uses       INT NOT NULL DEFAULT 0,          -- tổng số lần đã dùng
    used_count       INT NOT NULL DEFAULT 0,
    max_uses_per_user INT NOT NULL DEFAULT 1,         -- chống abuse
    starts_at        TIMESTAMP NOT NULL,
    expires_at       TIMESTAMP NOT NULL,
    is_active        BOOLEAN DEFAULT TRUE,
    created_at       TIMESTAMP DEFAULT NOW(),
    updated_at       TIMESTAMP DEFAULT NOW(),

    CONSTRAINT vouchers_code_unique UNIQUE (code),
    CONSTRAINT chk_expires_after_starts CHECK (expires_at > starts_at),
    CONSTRAINT chk_used_lte_total CHECK (used_count <= total_uses)
);

CREATE INDEX idx_vouchers_code     ON vouchers (code);           -- lookup khi apply
CREATE INDEX idx_vouchers_active   ON vouchers (is_active, expires_at);

orders — Đơn đặt vé (bảng trung tâm)
sqlCREATE TABLE orders (
    id               UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    order_code       VARCHAR(20) NOT NULL,            -- human-readable: ORD-20250601-XXXXX
    user_id          UUID NOT NULL REFERENCES users(id),
    voucher_id       UUID NULL REFERENCES vouchers(id),
    subtotal         DECIMAL(12, 2) NOT NULL,
    discount_amount  DECIMAL(12, 2) NOT NULL DEFAULT 0,
    total_amount     DECIMAL(12, 2) NOT NULL,
    status           ENUM('pending','awaiting_payment','paid','cancelled','expired','refunded') DEFAULT 'pending',
    idempotency_key  VARCHAR(255) NOT NULL,           -- chống duplicate booking
    notes            TEXT NULL,
    expires_at       TIMESTAMP NOT NULL,              -- pending order TTL (15 phút)
    paid_at          TIMESTAMP NULL,
    created_at       TIMESTAMP DEFAULT NOW(),
    updated_at       TIMESTAMP DEFAULT NOW(),

    CONSTRAINT orders_order_code_unique   UNIQUE (order_code),
    CONSTRAINT orders_idempotency_unique  UNIQUE (idempotency_key),  -- chống retry duplicate
    CONSTRAINT chk_total_positive         CHECK (total_amount >= 0)
);

CREATE INDEX idx_orders_user_id        ON orders (user_id);
CREATE INDEX idx_orders_status         ON orders (status);
CREATE INDEX idx_orders_expires_at     ON orders (expires_at) WHERE status = 'pending'; -- partial index
CREATE INDEX idx_orders_user_status    ON orders (user_id, status);
CREATE INDEX idx_orders_created_at     ON orders (created_at DESC);  -- admin dashboard

idempotency_key: Client gửi kèm một key duy nhất (UUID từ frontend). Nếu request bị retry, DB sẽ trả về order cũ thay vì tạo mới — chống duplicate booking hoàn toàn ở tầng DB.


order_items — Chi tiết từng loại vé trong đơn
sqlCREATE TABLE order_items (
    id                 UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    order_id           UUID NOT NULL REFERENCES orders(id) ON DELETE CASCADE,
    ticket_category_id UUID NOT NULL REFERENCES ticket_categories(id),
    quantity           INT NOT NULL CHECK (quantity > 0),
    unit_price         DECIMAL(12, 2) NOT NULL,      -- snapshot giá lúc mua
    subtotal           DECIMAL(12, 2) NOT NULL,
    created_at         TIMESTAMP DEFAULT NOW()
);

CREATE INDEX idx_order_items_order     ON order_items (order_id);
CREATE INDEX idx_order_items_category  ON order_items (ticket_category_id);

unit_price snapshot lại giá tại thời điểm đặt — tránh tình trạng giá bị thay đổi sau khi order được tạo.


voucher_usages — Lịch sử dùng voucher
sqlCREATE TABLE voucher_usages (
    id         UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    voucher_id UUID NOT NULL REFERENCES vouchers(id),
    user_id    UUID NOT NULL REFERENCES users(id),
    order_id   UUID NOT NULL REFERENCES orders(id),
    used_at    TIMESTAMP DEFAULT NOW(),

    CONSTRAINT voucher_usages_order_unique UNIQUE (order_id),          -- 1 order 1 voucher
    CONSTRAINT voucher_usages_user_voucher UNIQUE (user_id, voucher_id) -- chống dùng lại
);

CREATE INDEX idx_voucher_usages_voucher ON voucher_usages (voucher_id);
CREATE INDEX idx_voucher_usages_user    ON voucher_usages (user_id);

Unique constraint (user_id, voucher_id) là tầng bảo vệ cuối cùng chống voucher abuse — dù có race condition, DB sẽ reject insert thứ hai.


tickets — Vé điện tử (sinh ra sau khi thanh toán)
sqlCREATE TABLE tickets (
    id                 UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    order_item_id      UUID NOT NULL REFERENCES order_items(id),
    ticket_category_id UUID NOT NULL REFERENCES ticket_categories(id),
    user_id            UUID NOT NULL REFERENCES users(id),
    ticket_code        VARCHAR(50) NOT NULL,          -- mã vé duy nhất
    qr_code_url        VARCHAR(500),
    status             ENUM('valid','used','cancelled') DEFAULT 'valid',
    checked_in_at      TIMESTAMP NULL,
    created_at         TIMESTAMP DEFAULT NOW(),
    updated_at         TIMESTAMP DEFAULT NOW(),

    CONSTRAINT tickets_code_unique UNIQUE (ticket_code)
);

CREATE INDEX idx_tickets_user          ON tickets (user_id);
CREATE INDEX idx_tickets_code          ON tickets (ticket_code);      -- scan QR
CREATE INDEX idx_tickets_category      ON tickets (ticket_category_id);
CREATE INDEX idx_tickets_status        ON tickets (status);

operators — Nhân viên vận hành (Dashboard)
sqlCREATE TABLE operators (
    id            UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name          VARCHAR(255) NOT NULL,
    email         VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role          ENUM('admin','operator') DEFAULT 'operator',
    is_active     BOOLEAN DEFAULT TRUE,
    created_at    TIMESTAMP DEFAULT NOW(),
    updated_at    TIMESTAMP DEFAULT NOW(),

    CONSTRAINT operators_email_unique UNIQUE (email)
);

CREATE INDEX idx_operators_email  ON operators (email);
CREATE INDEX idx_operators_role   ON operators (role, is_active);

booking_logs — Audit trail
sqlCREATE TABLE booking_logs (
    id          UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    order_id    UUID NOT NULL REFERENCES orders(id),
    operator_id UUID NULL REFERENCES operators(id),   -- NULL nếu là hành động của user
    action      VARCHAR(100) NOT NULL,                -- 'status_changed', 'cancelled', ...
    payload     JSONB,                                -- diff before/after
    ip_address  VARCHAR(45),
    created_at  TIMESTAMP DEFAULT NOW()
);

CREATE INDEX idx_booking_logs_order    ON booking_logs (order_id);
CREATE INDEX idx_booking_logs_operator ON booking_logs (operator_id);
CREATE INDEX idx_booking_logs_created  ON booking_logs (created_at DESC);

Chiến lược chống các vấn đề chính
Vấn đềGiải pháp DBOversellingUPDATE ticket_categories SET available_quantity = available_quantity - ? WHERE id = ? AND available_quantity >= ? trong transaction — nếu 0 rows affected → sold outDuplicate bookingUNIQUE(idempotency_key) trên orders — retry cùng key sẽ bị DB rejectVoucher abuseUNIQUE(user_id, voucher_id) trên voucher_usages + increment used_count trong cùng transactionGiữ vé không muaexpires_at trên orders + cronjob release available_quantity về khi order expiredRace condition flash salePessimistic lock (SELECT ... FOR UPDATE) hoặc Optimistic lock với version column khi cần