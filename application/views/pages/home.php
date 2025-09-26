<html>
    <head>
        <title>Home Page</title>
            <!-- <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2021.3.914/styles/kendo.common.min.css"> -->
            <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2021.3.914/styles/kendo.bootstrap.min.css">            
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <!-- Kendo UI R3 2021 (2021.3.914) JS -->
            <script src="https://kendo.cdn.telerik.com/2021.3.914/js/kendo.all.min.js"></script>
    </head>
    <style>
        body{
            background-color: #f1dff7ff;
        }
        .center{
            place-items: center;
        }
        /* .text{
            text-indent: 20px;
        } */
        #btn a.k-button.k-primary{
            border-color: #ae1c74ff;
            background-color: #af317dff;
        }
        #intiting-title.k-card-header{
            background-color: #d59ce8ff;
            text-align: center;
        }
    </style>
    <body>
        <div></div>
        <div class="center">
            <div id="main-content" class="k-card" style="max-width:720px">
                <div id="intiting-title" class="k-card-header">
                    <div class="k-card-title">Bạn có muốn mở mang kiến thức?</div>
                </div>
                <div class="k-card-body">
                    <p class="text">🎬 Phim ảnh không chỉ là giải trí, mà còn là cách con người làm giàu tâm hồn, mở rộng trí tưởng tượng và soi chiếu lại chính cuộc sống của mình. Sau một ngày dài làm việc, một bộ phim hay có thể trở thành liều thuốc tinh thần, giúp ta thư giãn, đồng thời khơi gợi những suy ngẫm sâu sắc về tình yêu, gia đình, hay giá trị của bản thân.</p>
                    <p class="text">📖 Tài liệu này tuyển chọn một số bộ phim tiêu biểu, vừa mang tính nghệ thuật vừa gần gũi, để bạn có thể tham khảo, xem như gợi ý nhỏ cho hành trình cân bằng giữa công việc và đời sống tinh thần.</p>
                    <p class="text">👉 Để nhận tài liệu, vui lòng điền thông tin đăng ký trên website. Ngay sau đó, bạn sẽ nhận được danh sách phim để bắt đầu hành trình "điện ảnh" của bản thân.</p>
                </div>
                <div id="btn" class="k-card-actions k-card-actions-stretched">
                    <a href="<?= site_url('form') ?>" class="k-button k-primary">
                    <span class="k-icon k-i-download"></span> Click here
                    </a>
                </div>
            </div>
        </div>
    </body>
    <script>
        
    </script>
</html>