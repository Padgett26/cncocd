<?php
if ($login == 1) {

    if (isset($_FILES['newsletter']['name'])) {
        $time = time();
        $saveto = "newsletters/" . $time . ".pdf";
        move_uploaded_file($_FILES['newsletter']['tmp_name'], $saveto);

        $stmt = $db->prepare("SELECT name,email,created FROM newsletter");
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            $name = $row['name'];
            $email = $row['email'];
            $created = $row['created'];
            $code = md5($salt1 . $created . $salt2);
            $message = "
        <html><head></head><body>
        $name:<br><br>Thank you for subscribing to the Cheyenne County Conservation District newsletter.<br><br>
        Click this link to view the newsletter.<br>
        <a href='http://cheyennecountycd.com/newsletters/$time.pdf' style='text-decoration:underline;'>View newsletter.</a><br><br><br>
        If you do not want to receive emails from the Cheyeene County Conservation District Website, click the following link and you will be removed from the mailing list<br>
        <a href='http://cheyennecountycd.com/index.php?remove=$email&sec=$code' style='text-decoration:underline;'>Remove me from the mailing list.</a>
        </body></html>";
            // In case any of our lines are larger than 70 characters, we should
            // use wordwrap()
            $message = wordwrap($message, 70);
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: Cheyenne County Conservation District <dani.holzwarth@ks.nacdnet.net>' .
                    "\r\n";
            // Send
            mail($email, 'Cheyenne County Conservation District Newsletter',
                    $message, $headers);
        }
    }

    if (isset($_FILES['newcsppdf']['name'])) {
        $name = $_POST['created'];
        $saveto = "pdf/csp" . $name . ".pdf";
        move_uploaded_file($_FILES['newcsppdf']['tmp_name'], $saveto);
    }

    if (isset($_FILES['newedupdf']['name'])) {
        $name = $_POST['created'];
        $saveto = "pdf/edu" . $name . ".pdf";
        move_uploaded_file($_FILES['newedupdf']['tmp_name'], $saveto);
    }

    if ($_FILES["image1"]["size"] >= 1000) {
        $tmpFile = $_FILES["image1"]["tmp_name"];
        $image1Name = ($time + 1);
        list ($width1, $height1) = (getimagesize($tmpFile) != null) ? getimagesize(
                $tmpFile) : null;
        if ($width1 != null && $height1 != null) {
            $image1Type = getPicType($_FILES["image1"]['type']);
            processPic("photos", $image1Name . "." . $image1Type,
                    $tmpFile, 800, 150);
            $p1stmt = $db->prepare(
                    "UPDATE merch SET merchPic1=?, merchPic1Ext=? WHERE id=?");
            $p1stmt->execute(array(
                    $image1Name,
                    $image1Type,
                    $merchId
            ));
        }
    }

    if ($_FILES["image1"]["size"] >= 1000) {
        $tmpFile = $_FILES["image2"]["tmp_name"];
        $image2Name = ($time + 2);
        list ($width2, $height2) = (getimagesize($tmpFile) != null) ? getimagesize(
                $tmpFile) : null;
        if ($width2 != null && $height2 != null) {
            $image2Type = getPicType($_FILES["image2"]['type']);
            processPic("photos", $image2Name . "." . $image2Type,
                    $tmpFile, 800, 150);
            $p2stmt = $db->prepare(
                    "UPDATE merch SET merchPic2=?, merchPic2Ext=? WHERE id=?");
            $p2stmt->execute(array(
                    $image2Name,
                    $image2Type,
                    $merchId
            ));
        }
    }
    include "includes/adminProcessing.php";
}

if ($login == 0) {
    echo "Lets get you logged in:<br><form method='post' action='index.php?page=admin'>User Name:<br><input type='text' name='userid' size='40' maxlength='40' /><br><br>Password:<br><input type='password' name='pass' size='40' maxlength='40' /><br><input type='hidden' name='login' value='1' /><input type='submit' value=' -Login- ' /></form>";
} else {
    echo <<<_END
        <br><br>
        What changes do we need to make?<br><br>
        <a href='index.php?page=admin&choice=news'>Manage news feed</a><br>
    _END;
    // <a href='store/4khfaujgzaxscvcx/index.php'>Tree and shrub sale</a><br>
    echo <<<_END
        <a href='index.php?page=admin&choice=tsssetting'>Tree and shrub sale settings</a><br>
        <a href='index.php?page=admin&choice=csp'>Cost share programs</a><br>
        <a href='index.php?page=admin&choice=edu'>Education programs</a><br>
        <a href='index.php?page=admin&choice=nl'>Add a newsletter</a><br>
        <a href='index.php?page=admin&choice=about'>About us text</a><br>
        <a href='index.php?page=admin&choice=home'>Home page text</a><br>
        <a href='index.php?page=admin&choice=addy'>Contact info</a><br>
        <a href='index.php?page=admin&choice=links'>Add links to the links page</a><br>
        <a href='index.php?page=admin&choice=users'>Manage users</a><br><br><br>
    _END;

    if (filter_input(INPUT_GET, 'choice', FILTER_SANITIZE_STRING)) {
        $choice = filter_input(INPUT_GET, 'choice', FILTER_SANITIZE_STRING);
        if ($choice == "news") {
            echo "Lets create/edit/delete a news item.<br><br>
            <form method='post' action='index.php?page=admin'>
            <input type='radio' name='newsitem' value='new' id='new' /><label for='new'>New item</label><br>";
            $stmt = $db->prepare(
                    "SELECT id,title FROM news ORDER BY created DESC");
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $id = $row['id'];
                $title = $row['title'];
                echo "<input type='radio' name='newsitem' value='$id' id='$id' /><label for='$id'>$title</label><br>";
            }
            echo "<input type='submit' value=' -GO- ' /></form>";
        }
        if ($choice == "users") {
            $stmt = $db->prepare("SELECT id,name FROM users");
            $stmt->execute();
            echo "Create a new user, or select one to edit:<br><form method='post' action='index.php?page=admin'><select name='pickuser' size='1'><option value='new'>New user</option>";
            while ($row = $stmt->fetch()) {
                $id = $row['id'];
                $name = $row['name'];
                echo "<option value='$id'>$name</option>";
            }
            echo "</select><input type='submit' value=' -Go- ' /></form>";
        }
        if ($choice == "csp") {
            echo "Lets create/edit/delete a cost share program.<br><br>
            <form method='post' action='index.php?page=admin'>
            <input type='radio' name='cspitem' value='new' id='new' /><label for='new'>New CSP item</label><br>";
            $stmt = $db->prepare("SELECT id,title FROM csp");
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $id = $row['id'];
                $title = $row['title'];
                echo "<input type='radio' name='cspitem' value='$id' id='$id' /><label for='$id'>$title</label><br>";
            }
            echo "<input type='submit' value=' -GO- ' /></form>";
        }
        if ($choice == "edu") {
            echo "Lets create/edit/delete an education program.<br><br>
            <form method='post' action='index.php?page=admin'>
            <input type='radio' name='eduitem' value='new' id='new' /><label for='new'>New Education item</label><br>";
            $stmt = $db->prepare("SELECT id,title FROM education");
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $id = $row['id'];
                $title = $row['title'];
                echo "<input type='radio' name='eduitem' value='$id' id='$id' /><label for='$id'>$title</label><br>";
            }
            echo "<input type='submit' value=' -GO- ' /></form>";
        }
        if ($choice == "nl") {
            echo "Upload the newsletter here in a pdf format, and it will be sent to everyone on the newsletter emailing list, plus added to the archive on the newsletter page.<br>";
            echo "<form method='post' action='index.php?page=admin' enctype='multipart/form-data'><input type='file' name='newsletter' /><br><input type='submit' value=' Send ' /></form>";
        }
        if ($choice == "about") {
            echo "Lets edit your About Us text.<br><br>
        <form method='post' action='index.php?page=admin'>";
            $stmt = $db->prepare("SELECT * FROM about");
            $stmt->execute();
            $row = $stmt->fetch();
            $id = $row['id'];
            $title = $row['title'];
            $text = $row['text'];
            echo "Title:<br><input type='text' name='abouttitle' value='$title' size='80' maxlength='100' /><br><br>
        Text:<br><textarea name='abouttext' cols='60' rows='10'>$text</textarea><br><br>
        <input type='hidden' name='aboutup' value='$id' /><input type='submit' value=' -GO- ' /></form>";
        }
        if ($choice == "home") {
            echo "Lets edit your home page text.<br><br>
        <form method='post' action='index.php?page=admin'>";
            $stmt = $db->prepare("SELECT * FROM home");
            $stmt->execute();
            $row = $stmt->fetch();
            $id = $row['id'];
            $title = $row['title'];
            $text = $row['text'];
            echo "Title:<br><input type='text' name='hometitle' value='$title' size='80' maxlength='100' /><br><br>
        Text:<br><textarea name='hometext' cols='60' rows='10'>$text</textarea><br><br>
        <input type='hidden' name='homeup' value='$id' /><input type='submit' value=' -GO- ' /></form>";
        }
        if ($choice == "addy") {
            $stmt = $db->prepare(
                    "SELECT id,phone,address,email FROM sitesettings");
            $stmt->execute();
            $row = $stmt->fetch();
            $id = $row['id'];
            $phone = $row['phone'];
            $addy = $row['address'];
            $email = $row['email'];
            echo "<form method='post' action='index.php?page=admin'>
        Office phone: <input type='text' name='phone' value='$phone' size='20' maxlength='20' /><br>
        Address:<br><input type='text' name='address' value='$addy' size='60' maxlength='60' /><br>
        Email:<br><input type='text' name='email' value='$email' size='60' maxlength='60' /><br>
        <input type='hidden' name='updateaddy' value='$id' /><input type='submit' value=' -Update Info- ' /></form>";
        }
        if ($choice == "tsssetting") {
            $stmt = $db->prepare(
                    "SELECT id,orderdate,pudate,tssyear,startdate,taxrate FROM sitesettings");
            $stmt->execute();
            $row = $stmt->fetch();
            $id = $row['id'];
            $orderdate = $row['orderdate'];
            $pudate = $row['pudate'];
            $tssyear = $row['tssyear'];
            $startdate = $row['startdate'];
            $taxrate = $row['taxrate'];
            echo "<form method='post' action='index.php?page=admin'><table cellpadding='5px' cellspacing='0px' border='0'>";
            echo "<tr><td>Tax Rate:</td><td><input type='text' name='taxrate' value='$taxrate' size='7' />&#37;</td></tr>";
            echo "<tr><td>Sale year:</td><td><select name='year' size='1'>";
            for ($y = (date("Y") - 1); $y <= (date("Y") + 1); $y ++) {
                echo "<option value='$y'";
                if ($y == $tssyear)
                    echo " selected='selected'";
                echo ">$y</option>\n";
            }
            echo "</select></td></tr>";
            echo "<tr><td>Sale start date:</td><td> Day:<select name='startday' size='1'>";
            for ($d = 1; $d <= 31; $d ++) {
                echo "<option value='$d'";
                if ($d == date("j", $startdate))
                    echo " selected='selected'";
                echo ">$d</option>\n";
            }
            echo "</select> Month:<select name='startmonth' size='1'>";
            for ($m = 1; $m <= 12; $m ++) {
                echo "<option value='$m'";
                if ($m == date("n", $startdate))
                    echo " selected='selected'";
                echo ">$m</option>\n";
            }
            echo "</select></td></tr>";
            echo "<tr><td>Order by date:</td><td> Day:<select name='orderday' size='1'>";
            for ($d = 1; $d <= 31; $d ++) {
                echo "<option value='$d'";
                if ($d == date("j", $orderdate))
                    echo " selected='selected'";
                echo ">$d</option>\n";
            }
            echo "</select> Month:<select name='ordermonth' size='1'>";
            for ($m = 1; $m <= 12; $m ++) {
                echo "<option value='$m'";
                if ($m == date("n", $orderdate))
                    echo " selected='selected'";
                echo ">$m</option>\n";
            }
            echo "</select></td></tr>";
            echo "<tr><td>Pick up date:</td><td> Day:<select name='puday' size='1'>";
            for ($d = 1; $d <= 31; $d ++) {
                echo "<option value='$d'";
                if ($d == date("j", $pudate))
                    echo " selected='selected'";
                echo ">$d</option>\n";
            }
            echo "</select> Month:<select name='pumonth' size='1'>";
            for ($m = 1; $m <= 12; $m ++) {
                echo "<option value='$m'";
                if ($m == date("n", $pudate))
                    echo " selected='selected'";
                echo ">$m</option>\n";
            }
            echo "</select></td></tr>";
            echo "<tr><td colspan='2'><input type='hidden' name='tssyup' value='$id' /><input type='submit' value=' -Set- ' /></td></tr></table></form>";
        }
        if ($choice == "links") {
            $stmt = $db->prepare("SELECT id,title FROM links");
            $stmt->execute();
            echo "Select a link to edit, or select new to create one:<br><form method='post' action='index.php?page=admin'><select name='linkedit' size='1'><option value='new'>New</option>";
            while ($row = $stmt->fetch()) {
                $id = $row['id'];
                $title = $row['title'];
                echo "<option value='$id'>$title</option>";
            }
            echo "</select><input type='submit' value=' Next ' /></form>";
        }
    }
}