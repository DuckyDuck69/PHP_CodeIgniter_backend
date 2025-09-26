<html>
    <head>
        <title>Admin login</title>
        <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2021.3.914/styles/kendo.common.min.css">
        <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2021.3.914/styles/kendo.default.min.css">            
        
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Kendo UI R3 2021 (2021.3.914) JS -->
        <script src="https://kendo.cdn.telerik.com/2021.3.914/js/kendo.all.min.js"></script>
    </head>
    <style>
        body.app-layout {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(160deg, #f07070ff 0%, #faccf4ff 45%, #f1a8d9ff 100%);        
        }

        .page-center {
            flex: 1;                     
            display: grid;
            place-items: center;  /*center only the stack */          
            padding: 24px;
        }
        .stack { 
            width:100%; 
            max-width:560px; 
            height: auto;
            display:flex; 
            flex-direction:column; 
            gap:20px; 
        }
        .hero-card .k-card-body { 
            text-align:center; 
            padding:18px 20px; 
        }
        .hero-card h1 { 
            margin:0; 
            font-weight:800; 
            font-size:28px; 
        }
        .k-form { 
            padding:16px 20px; }
        .k-form-field { 
            display:flex; 
            flex-direction:column; 
            gap:6px; 
            margin-bottom:14px; 
        }
        #submitBtn.k-button.k-primary{
            background-color: #d63a3aff;
        }
        #topbar{
            background: transparent ;
            border-color: transparent;  
            box-shadow: none;
        }
        #goBackBtn{
            background-color: #2883faff;
        }

    </style>

    <body class="app-layout">
        <span id="notify"></span>
        <div id="topbar"></div>
        <div class="page-center">
            <div class="stack">
                <div class="k-card hero-card">
                    <div class="k-card-body">
                        <h1>Admin login</h1>
                    </div>
                </div>
                <div class="k-card">
                    <form id="login" method="post" action="<?= site_url('admin_login') ?>" class="k-form">
                        <div class="k-form-field">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="k-textbox" required>
                        </div>
                        <div class="k-form-field">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="k-textbox" required>
                        </div>
                        <div class="k-card-actions k-card-actions-stretched">
                            <button type="submit" id="submitBtn" class="k-button k-primary">
                            <span class="k-icon k-i-lock"></span> Log In
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </body>
    <script>
        $(function(){
            var notification = $("#notify").kendoNotification( {
                position: { top: 20, right: 20 },
                stacking: "down",
                autoHideAfter: 3000
            }).data("kendoNotification");

            <?php if (!empty($err)): ?>
                notification.show("Mật khẩu hoặc email không đúng. Vui lòng kiểm tra lại thông tin", "error");
            <?php endif; ?>
        });
        $("#topbar").kendoAppBar({
            items: [
            { type: "spacer" },
            { type: "spacer" },
            { type: "contentItem", template:
                '<a href="<?= site_url('form') ?>" id="goBackBtn" class="k-button k-primary">Trở về trang đăng kí</a>' }
            ]
        });
    </script>
</html>