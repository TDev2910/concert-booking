# Hướng dẫn Thiết lập và Vận hành Docker (Concert Booking Platform)

Tài liệu này giải thích cấu trúc Docker được xây dựng cho dự án Concert Booking, tập trung vào tính tự động hóa và khả năng chịu tải cao (High Traffic).

## 1. Tổng quan kiến trúc (Architecture)

Hệ thống được vận hành bởi 4 dịch vụ chính được tách biệt hoàn toàn (Separation of Concerns):

*   **Nginx (concert_web):** Web server chịu trách nhiệm tiếp nhận yêu cầu HTTP, phục vụ file tĩnh và chuyển tiếp yêu cầu xử lý logic sang PHP-FPM. Được tối ưu hóa cho môi trường Windows/WSL2.
*   **PHP-FPM 8.2 (concert_app):** Chạy mã nguồn Laravel. Tích hợp **Supervisor** để quản lý đồng thời tiến trình xử lý web và tiến trình hàng đợi (Queue Worker).
*   **MySQL 8.0 (concert_db):** Cơ sở dữ liệu chính. Được cấu hình với `mysql_native_password` để đảm bảo tương thích hoàn toàn với Laravel.
*   **Redis Alpine (concert_redis):** Thành phần then chốt cho hệ thống Flash Sale. Được sử dụng để xử lý **Atomic Locking** (ngăn chặn bán quá số lượng vé) và Caching.

## 2. Các điểm kỹ thuật nổi bật (Technical Highlights)

### 2.1. Quy trình khởi chạy tự động (Automation)
Thông qua file `entrypoint.sh`, nhà tuyển dụng chỉ cần chạy lệnh khởi động Docker, hệ thống sẽ tự động thực hiện:
1.  Kiểm tra và cài đặt thư viện (`composer install`).
2.  Tự động tạo file môi trường (`.env`).
3.  Sinh mã khóa ứng dụng (`key:generate`).
4.  Tự động chạy Migrations và Seed dữ liệu mẫu (`migrate --seed`).

### 2.2. Kiểm tra sức khỏe dịch vụ (Healthchecks)
Hệ thống sử dụng cơ chế `depends_on` kết hợp với `healthcheck`. Container ứng dụng (PHP) sẽ **chỉ khởi động** sau khi MySQL và Redis đã báo trạng thái "Healthy" (Sẵn sàng kết nối). Điều này loại bỏ hoàn toàn các lỗi kết nối DB khi vừa bật hệ thống.

### 2.3. Tối ưu hóa cho High Traffic
*   **Redis Tuning:** Cấu hình chính sách `allkeys-lru` để quản lý bộ nhớ thông minh.
*   **Atomic Locking:** Sẵn sàng cho việc triển khai `Cache::lock` để xử lý tranh chấp dữ liệu khi có hàng trăm yêu cầu đặt vé cùng lúc.

## 3. Hướng dẫn dành cho Người đánh giá (Quickstart)

Nhà tuyển dụng chỉ cần thực hiện 2 bước duy nhất:

```bash
# Bước 1: Chuẩn bị file môi trường (Từ thư mục gốc dự án)
cp .env.example .env

# Bước 2: Khởi động hệ thống
docker compose up -d
```

Hệ thống sẽ sẵn sàng tại địa chỉ: **[http://localhost:8080](http://localhost:8080)**


*Ghi chú: Toàn bộ cấu hình đã được xử lý lỗi xuống dòng (LF) thông qua `.gitattributes`, đảm bảo hoạt động ổn định trên cả Windows và Linux.*
