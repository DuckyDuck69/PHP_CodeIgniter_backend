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
            <form action="<?= site_url("dashboard_filter") ?>" method="POST">
                <label for="selection">Filter Choice </label>
                <?php $active = isset($active_filter) ? $active_filter : 'none'; ?>
                <select name="selectedOption" id="selection">
                    <!-- Make the dropdowns remember their picked filter option -->
                    <option value="none"             <?= $active==='none' ? 'selected' : '' ?>>None</option>
                    <option value="email_verified"   <?= $active==='email_verified' ? 'selected' : '' ?>>Đã xác nhận email</option>
                    <option value="email_not_verified" <?= $active==='email_not_verified' ? 'selected' : '' ?>>Chưa xác nhận email</option>
                    <option value="pdf_clicked"      <?= $active==='pdf_clicked' ? 'selected' : '' ?>>Đã tải tài liệu</option>
                    <option value="pdf_not_clicked"  <?= $active==='pdf_not_clicked' ? 'selected' : '' ?>>Chưa tải tài liệu</option>
                </select>
                <br><br>
                <input type="submit" value="Filter">
            </form>

            <form action="<?= site_url('home_page')?>">
                <button type="submit" id="logoutBtn">Log out</button>
            </form>
        </div>
        <br>
        <br>

        <!-- A form to send email only if we filter the table by users who haven't sent email or  received pdf -->
        <?php if (in_array($active, ['email_not_verified','pdf_not_clicked'], true)): ?>
        <form action="<?= site_url('admin/send_reminder') ?>" method="POST" style="" id="send-reminder">
            <input type="hidden" name="filter" value="<?= htmlspecialchars($active, ENT_QUOTES, 'UTF-8') ?>">
            <button style="background-color:yellow" type="submit" id="queryBtn"><?= $active == 'email_not_verified' ? 'Gửi lại email' : 'Gửi lại tài liệu' ?></button>
        </form>
        <?php endif; ?>
        <br>
        <br>
        <table id="grid" border="1" cellspacing="0" cellpadding="6">
            <caption></caption>
            <thead>
                <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Age</th>
                <th>Job</th>
                <th>Location (long, lat)</th>
                <th>Email Verified</th>
                <th>File Clicked</th>
                <th>Verify opened</th>
                <th>Created at</th>
                </tr>   
            </thead>
            <tbody id ="rows">

                <?php foreach($user as $u): ?>
                    <?php 
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
                        // if(isset($lat) && isset($long)) {
                        //     $apiKey = getenv('SERP_MAP_API');
                        //     $url = "https://serpapi.com/search.json?engine=google_maps_reverse&lat=$lat&lng=$long&api_key=$apiKey";
                        //     $response = file_get_contents($url);
                        //     $data = json_decode($response, true);

                        //     $loc = isset($data['local_results']['address']) ?  $data['local_results']['address'] : 'Unknown location';

                        //     echo  (string)$loc;
                        // }
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($u['_id']) ?></td>
                        <td><?= htmlspecialchars(isset($u['identity']['full_name']) ? $u['identity']['full_name'] : '') ?></td>
                        <td><?= htmlspecialchars(isset($u['identity']['email']) ? $u['identity']['email'] :  '') ?></td>
                        <td><?= htmlspecialchars(isset($u['profile']['age']) ?  $u['profile']['age'] : '') ?> </td>
                        <td><?= htmlspecialchars(isset($u['profile']['occupation']) ?  $u['profile']['occupation'] :'')?></td>
                        <td><?= htmlspecialchars($loc) ?> </td>
                        <td><?= !empty($u['engagement']['verified']['status']) ? 'Yes' : 'No' ?> </td>
                        <td><?= !empty($u['engagement']['pdf_click']['clicked']) ? 'Yes' : 'No' ?> </td>
                        <td><?= !empty($u['engagement']['pdf_click']['clicked']) ? 'Yes' : 'No' ?> </td>
                        <td><?= !empty($u['audit']['created_at']) ? $u['audit']['created_at'] : 'Failed to fetch created_at time' ?> </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </body>
    <script>
        $("#logoutBtn").kendoButton();
        
        $("#notify").kendoNotification({
            stacking: "down",
            position: { top: 20, right: 20 },
            autoHideAfter: 3000
        })
        $("#send-reminder").on("submit", function (e) {
            //Stop redirecting 
            e.preventDefault();

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

        var ddl = $("#selection").kendoDropDownList({
            optionLabel: "— Chọn bộ lọc —",         
            animation: {                                 
            open:  { effects: "fadeIn"  },
            close: { effects: "fadeOut" }
            }
        }).data("kendoDropDownList");
        ddl.value("<?= $active ?>");
    </script>
        

</html>