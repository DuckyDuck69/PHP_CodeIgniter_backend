<html>
    <head>
        <title>Admin Dashboard</title>
    </head>
    <body>
        <br>
        <h1>Admin Management Center</h1>
        <br>
        <br>

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
        <br>
        <br>

        <!-- A form to send email only if we filter the table by users who haven't sent email or  received pdf -->
        <?php if (in_array($active, ['email_not_verified','pdf_not_clicked'], true)): ?>
        <form action="<?= site_url('admin/send_reminder') ?>" method="POST" style="">
            <input type="hidden" name="filter" value="<?= htmlspecialchars($active, ENT_QUOTES, 'UTF-8') ?>">
            <button style="background-color:yellow" type="submit"><?= $active == 'email_not_verified' ? 'Gửi lại email' : 'Gửi lại tài liệu' ?></button>
        </form>
        <?php endif; ?>
        <br>
        <br>
        <table border="1" cellspacing="0" cellpadding="6">
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
                </tr>   
            </thead>
            <tbody id ="rows">

                <?php foreach($user as $u): ?>
                    <?php 
                        //Convert coor array to string
                        $coords = $u['location']['coords']['coordinates'] ?? null;
                        if ($coords !== null) {
                            $long = $coords[1] ?? '';
                            $lat  = $coords[0] ?? '';
                            $loc = $lat !== '' && $long !== '' ? $lat . ', ' . $long : 'not enough coor info';
                        } else {
                            $loc = 'no coor';
                        }
                    ?>
                <tr>
                    <td><?= htmlspecialchars($u['_id']) ?></td>
                    <td><?= htmlspecialchars($u['identity']['full_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($u['identity']['email'] ?? '') ?></td>
                    <td><?= htmlspecialchars($u['profile']['age'] ?? '') ?> </td>
                    <td><?= htmlspecialchars($u['profile']['occupation'] ?? '')?></td>
                    <td><?= htmlspecialchars($loc) ?> </td>
                    <td><?= !empty($u['engagement']['verified']['status']) ? 'Yes' : 'No' ?> </td>
                    <td><?= !empty($u['engagement']['pdf_click']['clicked']) ? 'Yes' : 'No' ?> </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </body>
        

</html>