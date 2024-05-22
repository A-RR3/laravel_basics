<?php
namespace App\Models;

class Listing {
public static function all(){
    return [
        [
            'id'=> 1,
            'title' => 'title1',
            'description' => '
            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,
            and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
            '
        ],
        [
            'id'=> 2,
            'title' => 'title2',
            'description' => '
            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,
            and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
            '
        ]
        ];
  }

  public static function find ($id){
    $listings = self::all();
    foreach($listings as $listing){
        if($listing['id'] == $id){
            return $listing;
        }
    }
  }
}
