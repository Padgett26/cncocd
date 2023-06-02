<?php

function processPdf ($userId, $time, $pdf1or2, $file, $artId, $db)
{
    $pdfName = $time + $pdf1or2;
    $saveto = "userPics/$userId/$pdfName.pdf";
    move_uploaded_file($file, $saveto);
    if (filesize("userPics/$userId/$pdfName.pdf") <= 1000) {
        unlink("userPics/$userId/$pdfName.pdf");
    }
    if (file_exists("userPics/$userId/$pdfName.pdf")) {
        $pdfstmt = $db->prepare(
                "UPDATE articles SET pdf" . $pdf1or2 . "=? WHERE id=?");
        $pdfstmt->execute(array(
                $pdfName,
                $artId
        ));
    }
}

function deletePdf ($userId, $pdf1or2, $artId, $db)
{
    $stmt = $db->prepare("SELECT pdf" . $pdf1or2 . " FROM articles WHERE id=?");
    $stmt->execute(array(
            $artId
    ));
    $row = $stmt->fetch();
    if (file_exists("userPics/$userId/" . $row[0] . ".pdf")) {
        unlink("userPics/$userId/" . $row[0] . ".pdf");
    }
    $stmt2 = $db->prepare(
            "UPDATE articles SET pdf" . $pdf1or2 . "='0' WHERE id=?");
    $stmt2->execute(array(
            $artId
    ));
}