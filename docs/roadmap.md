# Project Development Roadmap - Concert Booking Platform

Dự án được phát triển theo mô hình **Git Flow**, chia nhỏ thành các tính năng (features) độc lập để đảm bảo tính ổn định và dễ dàng kiểm soát chất lượng qua CI/CD.

## Giai đoạn 1: Cơ sở dữ liệu và Dữ liệu mẫu (Data Foundation)
*   **Mục tiêu:** Thiết lập "xương sống" dữ liệu cho toàn bộ hệ thống.
*   **Nhiệm vụ:**
    *   [ ] Thiết kế và chạy Migrations cho 10 bảng chính (UUID based).
    *   [ ] Xây dựng Seeders cho Users, Concerts và Ticket Categories.
*   **Nhánh:** `feature/database-setup`

## Giai đoạn 2: API Danh sách và Khám phá (Discovery API)
*   **Mục tiêu:** Cung cấp dữ liệu cho phía người dùng cuối.
*   **Nhiệm vụ:**
    *   [ ] API lấy danh sách Concert (có phân trang, tìm kiếm).
    *   [ ] API xem chi tiết Concert và trạng thái hạng vé.
*   **Nhánh:** `feature/concert-listing`

## Giai đoạn 3: Hệ thống Đặt vé chịu tải cao (Core Booking Engine)
*   **Mục tiêu:** Xử lý logic đặt vé, chống tranh chấp dữ liệu (Race Condition).
*   **Nhiệm vụ:**
    *   [ ] API Đặt vé (Store Booking).
    *   [ ] Tích hợp Redis Atomic Lock để chống bán quá số lượng (Overselling).
    *   [ ] Xử lý Idempotency Key chống trùng đơn hàng.
    *   [ ] Cơ chế giải phóng vé (Release tickets) cho các đơn hàng hết hạn thanh toán.
*   **Nhánh:** `feature/booking-system`

## Giai đoạn 4: Giao diện Người dùng (Frontend Integration)
*   **Mục tiêu:** Hiện thực hóa trải nghiệm người dùng trên trình duyệt.
*   **Nhiệm vụ:**
    *   [ ] Xây dựng trang chủ (Concert List) bằng Vue 3 + PrimeVue.
    *   [ ] Xây dựng luồng đặt vé (Booking Flow) tích hợp API.
*   **Nhánh:** `feature/frontend-ui`

## Giai đoạn 5: Quản trị và Theo dõi (Operations & Logs)
*   **Mục tiêu:** Cung cấp công cụ cho nhân viên vận hành.
*   **Nhiệm vụ:**
    *   [ ] Xây dựng hệ thống Booking Logs (Audit Trail).
    *   [ ] API/Giao diện quản lý đơn hàng cơ bản cho Operator.
*   **Nhánh:** `feature/admin-dashboard`

## Giai đoạn 6: Hoàn thiện và Bàn giao (Final Delivery)
*   **Mục tiêu:** Đóng gói dự án chuyên nghiệp.
*   **Nhiệm vụ:**
    *   [ ] Tích hợp Swagger (API Documentation).
    *   [ ] Xuất Postman Collection.
    *   [ ] Merge `dev` vào `main` và bàn giao.

---
*Lộ trình này có thể điều chỉnh tùy theo phản hồi thực tế trong quá trình phát triển.*
