<html>
    <head>
        <title>Submit Page</title>
        <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2021.3.914/styles/kendo.common.min.css">
        <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2021.3.914/styles/kendo.bootstrap.min.css"> 

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Kendo UI R3 2021 (2021.3.914) JS -->
        <script src="https://kendo.cdn.telerik.com/2021.3.914/js/kendo.all.min.js"></script>
        <!-- Culture Script -->
        <script src="https://kendo.cdn.telerik.com/2021.3.914/js/cultures/kendo.culture.vi-VN.min.js"></script>

    </head>
    <style>     
    body {
        min-height: 100vh;
        background: linear-gradient(160deg, #a0b3f8ff 0%, #dcf2b3ff 45%, #f7f7fb 100%);
    }
    #topbar.k-appbar {
        background: transparent;    
        border-color: transparent;  
        box-shadow: none;          
    }
    .form-screen {
        min-height: 100vh;
        display: grid;
        place-items: center;  /**center */
        padding: 24px;
    }
    /*card wrapper */
    .form-card {
        width: 80%;
        max-width: 560px;
    }
    /* fields */
    .k-form-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 16px;
    }
    .inline-group {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .k-card-title{
        text-align: center;
        font-weight: bold;
    }
    .k-card-header{
        background-color: #9ae98aff;
    }
    #admin-login.k-button.k-primary {
        color: #040404ff;
        background: transparent;
        background-color: #ed9d6fff;
    }
    #form-title.k-card-header{
        background-color: #eeff6cff;
    }
    </style>
    <body>
        <div id="topbar"></div>
        <div class="form-screen">
            <div class="k-card form-card">
                <div id="form-title" class="k-card-header">
                    <div class="k-card-title">Điền thông tin của bạn</div>
                </div>
                <form id="form" method="post" action="<?= site_url('form_submit') ?>">
                    <div class="k-card-body">
                        <div class="k-form-field">
                            <label for="email">Email</label>
                            <input name="email" id="email" class="k-textbox" type="email" required/>
                        </div>

                        <div class="k-form-field">
                            <label for="fullname">Họ và tên</label>
                            <input name="fullname" id="fullname" class="k-textbox" type="text" required/>
                        </div>

                        <div class="k-form-field">
                            <label>Giới tính</label>
                            <div class="inline-group">
                                <label><input type="radio" name="gender" value="Female" name="gender"/> Female</label>
                                <label><input type="radio" name="gender" value="Male" name="gender"/> Male</label>
                            </div>
                        </div>

                        <div class="k-form-field">
                            <label for="age">Ngày sinh</label>
                            <input name="age" class="k-textbox" id="age" required/>
                        </div>

                        <div class="k-form-field">
                            <label for="job">Nghề nghiệp</label>
                            <input id="job" name="job" class="k-textbox" type="text" required/>
                        </div>  
                        <div class="k-card-actions k-card-actions-stretched">
                            <button type="submit" class="k-button k-primary">Submit</button>
                        </div>
                    </div>
                    <input type="hidden" id="lat"  name="latitude">
                    <input type="hidden" id="long" name="longitude">
                </form>
                
            </div>
        </div>
      
    </body>
    <script>
        kendo.culture("vi-VN");

        $("#age").kendoDatePicker({
            format: "dd/MM/yyyy",
            parseFormats: ["dd/MM/yyyy","d/M/yyyy","ddMMyyyy"], // accept a few inputs
            dateInput: true,   // Masked input (DD/MM/YYYY)
            max: new Date()    //Prevent future dates if it's DOB
        });

        $("#topbar").kendoAppBar({
            items: [
            { type: "spacer" },
            { type: "spacer" },
            { type: "contentItem", template:
                '<a href="login" id="admin-login" class="k-button k-primary"><span class="k-icon k-i-lock"></span> Đăng nhập quản trị viên</a>' }
            ]
        });

        //Geo location handle
        const form = document.getElementById('form')
        const latitude = document.getElementById('lat')
        const longitude = document.getElementById('long')
        form.addEventListener('submit', (e)=>{
            e.preventDefault() //wait for the geographic 

            if(!navigator.geolocation){
                form.submit();
                return
            }

            navigator.geolocation.getCurrentPosition((position) =>{
                latitude.value = position.coords.latitude;
                longitude.value = position.coords.longitude;
                form.submit();
            }, 
            ()=>{
                form.submit() 
            })
        })
        
        
    </script>
</html>