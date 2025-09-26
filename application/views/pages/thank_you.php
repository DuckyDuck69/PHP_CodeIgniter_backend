<html>
    <head>
        <title>Thank you</title>
        <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2021.3.914/styles/kendo.common-bootstrap.min.css" />
        <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2021.3.914/styles/kendo.bootstrap.min.css" >
        
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Kendo UI R3 2021 (2021.3.914) JS -->
        <script src="https://kendo.cdn.telerik.com/2021.3.914/js/kendo.all.min.js"></script>
    </head>
    <style>
        body{
            min-height: 50vh;
        background: linear-gradient(160deg, #76efaaff 0%, #dcf2b3ff 45%, #77d480ff 100%);
        }
        .k-state-selected{
            background-color: #a46be5ff;
        }
        .k-panelbar .k-link {
            padding: 12px 18px;  /* top/bottom, left/right */
        }
        .k-panelbar .k-content {
            padding: 20px 24px !important;  
            line-height: 1.7;
            font-size: 16px;
        }
        .text{
            padding: 20px 24px;
            line-height: 1.6;
        }
        .section{
            width: 50%;
            margin-left: 10%;
            margin-bottom: 5%;
        }
        #nextSteps {
            border-radius: 12px;       
            overflow: hidden;         
            box-shadow: 0 4px 10px rgba(0,0,0,0.1); 
        }
        #return{
            background-color: #ffff6fff;
        }
    </style>
    <body>
        <div class="page">
            <h2><strong>Cảm ơn bạn đã điền đơn!</strong></h2>
            <h4>Kiểm tra email để nhận thư xác minh</h4>

            <h4>Những bước tiếp theo:</h4>
            <!-- PanelBar Section -->
            <div class="section">
                <ul id="nextSteps">
                    <li class="k-state-active">
                        <span class="k-link k-state-selected">1. Xác nhận email của bạn</span>
                        <div class="text">
                            Mở gmail của bạn và bấm vào <strong><a href="https://mail.google.com/">link xác thực</a></strong> để xác nhận email của bạn.
                        </div>
                    </li>
                    <li>
                        <span class="k-link">2. Nhận tài liệu</span>
                        <div class="text">
                            Khi đã xác nhận, hệ thống sẽ gửi bạn một email chứa tài liệu ngay lập tức!
                        </div>
                    </li>
                    <li>
                        <span class="k-link">3. Tận hưởng!</span>
                        <div class="text">
                            Tận hưởng những bộ phim tuyệt vời trong thời gian rảnh của bạn! 🎬
                        </div>
                    </li>
                </ul>
            </div>
            <form action="<?= site_url('home_page') ?>">
                <button id="return">Return to Home</button>
            </form>
        </div>
    </body>
     <script>
        $("#return").kendoButton();
        $(document).ready(function() {
            $("#nextSteps").kendoPanelBar({
                expandMode: "single"
            });
        });
    </script>
</html>
