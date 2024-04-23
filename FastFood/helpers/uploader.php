<?php
class Uploader
{
    public static function uploadImage($file)
    {
        if (!empty($file["tmp_name"]) && is_uploaded_file($file["tmp_name"])) {
            $targetDirectory = "uploads/";
            $allowedExt = ["jpg", "jpeg", "png"];
            $targetFile = $targetDirectory . basename($file["name"]);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            # Kiểm tra xem file có phải là hình ảnh thực sự hay không
            if (getimagesize($file["tmp_name"]) === false) return false;

            # Kiểm tra kích thước file
            if ($file["size"] > 500000) return false;

            # Kiểm tra định dạng file
            if (!in_array($imageFileType, $allowedExt)) return false;

            $newFileName = uniqid() . '-' . basename($file["name"]);
            $targetFile = $targetDirectory . $newFileName;

            if (move_uploaded_file($file["tmp_name"], $targetFile)) return $newFileName;
            else return false;
        } else return false;
    }
}
