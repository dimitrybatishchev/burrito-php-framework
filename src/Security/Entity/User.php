<?php
namespace Security\Entity;

use Burrito\Framework\ActiveRecord\ActiveRecord as ActiveRecord;

class User extends ActiveRecord{
    public function tableName() { return 'user'; }
}