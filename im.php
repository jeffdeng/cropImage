<?php

$source = imagecreatefrompng( 'photo.png');

$mask =imagecreatefrompng('cur3-3.png');

$xSize_picture = imagesx( $source  );
$ySize_picture = imagesy( $source );
$xSize = imagesx( $mask );
$ySize = imagesy( $mask );

$rand_x=rand($xSize_picture/2,$xSize_picture-$xSize);
$rand_y=rand($ySize,$ySize_picture-$ySize);


/////////////////////////////========抠出小图片=========/////////////////////////////////

//$source = imagecreatefrompng( 'photo.png');

imagealphamask($source, $mask ,$xSize,$ySize,$rand_x,$rand_y);

/////////////////////////////=======合并出背景图片==========/////////////////////////////////

$mask =imagecreatefrompng('cur_tou40lh.png');//换一个透明度的蒙版图片

imagealphamerge($source, $mask,$xSize,$ySize,$rand_x,$rand_y);
imagedestroy($source);

/////////////////////////////=================/////////////////////////////////


function imagealphamerge($picture, $mask,$xSize,$ySize,$rand_x,$rand_y ) {
    
	//imagecopymerge($picture, $mask, $rand_x, $rand_y, 0, 0, $xSize,$ySize , 100);
	imagecopy($picture, $mask, $rand_x, $rand_y, 0, 0, $xSize,$ySize);

	// 将图像保存到文件，并释放内存
	header('Content-type: image/png');
	imagepng($picture);
	imagepng($picture,'source_test.png');	
}



function imagealphamask($picture, $mask ,$xSize,$ySize,$rand_x,$rand_y) {
   
    $newPicture = imagecreatetruecolor( $xSize, $ySize );
    imagesavealpha( $newPicture, true );
    imagefill( $newPicture, 0, 0, imagecolorallocatealpha( $newPicture, 0, 0, 0, 127 ) );
    
    for( $x = 0; $x < $xSize; $x++ ) {
        for( $y = 0; $y < $ySize; $y++ ) {
            $alpha = imagecolorsforindex( $mask, imagecolorat( $mask, $x, $y ) );
            $alpha = 127 - floor( $alpha[ 'red' ] / 2 );

            $color = imagecolorsforindex( $picture, imagecolorat( $picture, $x+$rand_x, $y+$rand_y ) );
            imagesetpixel( $newPicture, $x, $y, imagecolorallocatealpha( $newPicture, $color[ 'red' ], $color[ 'green' ], $color[ 'blue' ], $alpha ) );
        }
    }
    
    imagepng( $newPicture ,'cur_test.png');
	imagedestroy($newPicture);
  }

