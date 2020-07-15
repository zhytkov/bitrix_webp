<?php

class WebpImage
{

   /**
    * Выполняет конвертацию файлов png, jpeg в конечный .webp
    * @param $i_src
    * @return bool
    */
   public static function convert($i_src){

      $is_type = '';

      $search = strripos(strtolower($i_src), '.jpg');
      if ($search) {
         $is_type = 1;
      }

      $search = strripos(strtolower($i_src), '.jpeg');
      if ($search) {
         $is_type = 1;
      }

      $search = strripos(strtolower($i_src), '.png');
      if ($search) {
         $is_type = 2;
      }

      $getWebp = str_replace(['.jpeg', '.png', '.jpg'], ".webp", strtolower($i_src));

      if ($is_type == 2)
      {
         $im = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . $i_src);
         imagepng($im, $_SERVER['DOCUMENT_ROOT'] . $i_src, 72);
      }
      else
      {
         $im = imagecreatefromjpeg($_SERVER['DOCUMENT_ROOT'] . $i_src);
         imagejpeg($im, $_SERVER['DOCUMENT_ROOT'] . $i_src, 72);

      }

      imagewebp($im, $_SERVER['DOCUMENT_ROOT'] . $getWebp, 92);
      imagedestroy($im);

      if (file_exists($_SERVER['DOCUMENT_ROOT'] . $getWebp))
      {
         return true;
      } else {
         return false;
      }
   }

   /**
    * Возращает html строку с универсальным выводом картинки jpeg, png в случае если браузер не поддерживает WebP
    * @param $webp_src путь к картинке WebP
    * @param $alt_src альтернативный путь к картирнке в других форматах
    * @param $alt_text текст для картинки $alt_src
    * @param $title
    * @param $class
    * @return string
    */
   public static function get($webp_src, $alt_src, $alt_text, $title, $class){
      $return = '';

      $return = '<picture>
         <source type="image/webp" srcset="'.$webp_src.'">
         <source type="" srcset="'.$alt_src.'">
         <img data-lazy="'.$alt_src.'" src="'.$alt_src.'" alt="'.$alt_text.'" title="'.$title.'" class="'.$class.'">
      </picture>';

      return $return;
   }

   /**
    * @param string $i_src
    * @param string $i_alt
    * @param string $src
    * @param string $i_title
    * @param null $i_class
    */
   public static function getInstance($i_src, $i_alt = null, $i_title = null, $i_class = null){

      $code = '';
      $getWebp = str_replace(['.jpeg', '.png', '.jpg'], ".webp", strtolower($i_src));

      if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $getWebp))
      {
         $convert = self::convert($i_src);
         if($convert){
            $code = self::get($getWebp, $i_src, $i_alt, $i_title, $i_class);
         } else {
            $code = '<img data-lazy="' . $i_src . '" src="' . $i_src. '" alt="' . $i_alt . '">';
         }
      } else {
        $code = self::get($getWebp, $i_src, $i_alt, $i_title, $i_class);
      }

      return $code;
   }

   /**
    * @param string $i_src
    */
   public static function getInstanceSrc($i_src){

      $user_agent = $_SERVER["HTTP_USER_AGENT"];
      //if (strpos($user_agent, "Safari") !== false) return $i_src;
      $getWebp = str_replace(['.jpeg', '.png', '.jpg'], ".webp", strtolower($i_src));
      if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $getWebp))
      {
         $convert = self::convert($i_src);
         if($convert){
            return $getWebp;
         } else {
            return $i_src;
         }
      } else {
         return $getWebp;
      }
   }

}
