<html>
    <head>
        <title>Admin Dashboard</title>
        <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2021.3.914/styles/kendo.common.min.css">
        <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2021.3.914/styles/kendo.default.min.css">            
        
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Kendo UI R3 2021 (2021.3.914) JS -->
        <script src="https://kendo.cdn.telerik.com/2021.3.914/js/kendo.all.min.js"></script>
    </head>
    <style>
        .k-grid-header .k-header {
            white-space: normal !important;
            text-overflow: clip !important;
        }
        #title{
            text-align: center;
        }
        #logout{
            display: flex;
            align-items: start;
            justify-content: space-between;
        }
        #logoutBtn{
            background-color: #e74a4aff;
        }
    </style>
    <body>
        <br>
        <div id="notify"></div>
        <div id="title">
            <h1>Admin Management Center</h1>
        </div>
        <br>
        <br>
        <div id="logout">
            <form  id="send-reminder" action="<?= site_url("admin/send_reminder") ?>" method="POST">
                <label for="selection">Chọn loại email</label>
                    <select id="selection" name="selection">
                        <option value="verify">Thư xác nhận</option>
                        <option value="file">Thư tài liệu</option>
                    </select>
                <input type="hidden" id="filter" name="filtered_user">
                <button type="submit">Send Reminder</button>
            </form>

            <form action="<?= site_url('home_page')?>">
                <button type="submit" id="logoutBtn">Log out</button>
            </form>
        </div>
        <br>
        <br>
        <br>
        <br>
            <div id="dashboard">
                <div id="users"
                    data-role = "grid"
                    data-bind="source: users"
                    data-filterable='{"mode":"row"}'
                    data-columns='[
                    { "field": "_id", "hidden": true },
                    { field: "name", title: "Họ tên" },
                    { field: "email", title: "Email" },
                    { field: "gender", title: "Giới tính"},
                    { field: "age", title: "Ngày sinh"},
                    { field: "job", title: "Nghề nghiệp"},
                    { field: "location", title: "Tọa độ"},
                    { field: "read_verify", title: "Đã xem verify mail  "},
                    { field: "verify", title: "Đã verify"},
                    { field: "download_pdf", title: "Đã tải pdf"},
                ]'
                    data-pageable="true"
                    data-scrollable="true">
                </div>
            </div>
    </body>
    <script>
        var usersData = <?php 
        $rows = [];
        foreach($user as $u){
            //Convert coor array to string
            $coords = isset($u['location']['coords']['coordinates']) ? $u['location']['coords']['coordinates'] : null;
            if ($coords !== null) {
                $long = isset($coords[1]) ? $coords[1]: '';
                $lat  = isset($coords[0]) ? $coords[0]: '';
                $loc = $lat !== '' && $long !== '' ? $lat . ', ' . $long : 'not enough coor info';
            } 
            else {
                $loc = 'no coor';
            }
            $rows[] = [
                '_id'               => isset($u['_id']) ? $u['_id'] : '',
                'name'             => isset($u['identity']['full_name']) ? $u['identity']['full_name'] : '',
                'email'            => isset($u['identity']['email']) ? $u['identity']['email'] : '',
                'gender'           => isset($u['profile']['gender']) ? $u['profile']['gender'] :'',
                'age'              => isset($u['profile']['age']) ? $u['profile']['age'] : null,
                'job'              => isset($u['profile']['occupation']) ? $u['profile']['occupation'] : '',
                'location'         => $loc,
                'read_verify'         => isset($u['engagement']['email_open']['verify']) ? (bool)$u['engagement']['email_open']['verify'] : false,
                //'read_verify_date'      => isset($u['engagement']['email_open']['verify_opened_time']) ? $u['engagement']['email_open']['verify_opened_time'] : 'Not yet',
                'verify'      => isset($u['engagement']['verified']['status']) ? (bool)$u['engagement']['verified']['status'] : false,
                'download_pdf' => isset($u['engagement']['pdf_click']['clicked']) ? (bool)$u['engagement']['pdf_click']['clicked'] : false,  
                //'created_at'       => isset($u['audit']['created_at']) ? $u['audit']['created_at'] : 'Failed to fetch created_at time',
            ];
        }
        $output = json_encode($rows, JSON_UNESCAPED_UNICODE);
        log_message('info','Output row: '. $output);
        echo $output;
        ?>
        
        var viewModel = kendo.observable({
            users : new kendo.data.DataSource({
                    data: usersData,
                    schema:{
                        model: {
                            fields: {
                                read_verify:      { type: "boolean" },
                                read_verify_date: { type: "date" },
                                verify:           { type: "boolean" },
                                download_pdf:     { type: "boolean" }
                            }
                        },
                    },
                pageSize: 25
            })
        });
        kendo.bind($("#dashboard"), viewModel);
        $("#logoutBtn").kendoButton();
        
        $("#notify").kendoNotification({
            stacking: "down",
            position: { top: 20, right: 20 },
            autoHideAfter: 3000
        })
        $("#send-reminder").on("submit", function (e) {
            //Stop redirecting 
            e.preventDefault();

            var grid = $("#users").data("kendoGrid");
            if (!grid) {
                console.error("Grid instance not found");
                return;
            }
            

            var dataSource = grid.dataSource;
            var allData = dataSource.data(); // all loaded data
            var filters = dataSource.filter(); // current filter config

            //Apply filters manually
            var query = new kendo.data.Query(allData);
            var filteredData = query.filter(filters).data;

            var result = filteredData.map(item => item.toJSON());
            document.getElementById("filter").value = JSON.stringify(result);

            console.log("Posting users:", result.length, result);

            //Use Jquery AJAX's helper .post() to send form data to the server
            $.post(this.action, $(this).serialize())  //Take form action, turn them to key-val format
                .done(res => showMsg("Đã gửi email nhắc nhở cho " + (res.sent || 0) + " người.", "success"))
                .fail(() => showMsg("Gửi nhắc nhở thất bại.", "error"));
            });

        function showMsg(msg, type){
            $("#notify").data("kendoNotification").show(msg, type);
        }

        $(document).ready(function(){
            $("#grid").kendoGrid({
                sortable: true,
                filterable: true,
                pageable: {
                    refresh: true,        // adds a refresh button
                    pageSizes: [10, 25, 50], // dropdown to change page size
                    buttonCount: 5         // number buttons shown
                }
            });
        });

    </script>
        

</html>