<?php

require __DIR__ . '/vendor/autoload.php';

use Articus\DataTransfer\Annotation as DTA;
use OpenAPI\DataTransfer\Annotation as ODTA;

/** @var \Psr\Container\ContainerInterface $container */
$container = require __DIR__ . '/config/container.php';

/** @var \Articus\DataTransfer\Service $dataTransfer */
$dataTransfer = $container->get(\Articus\DataTransfer\Service::class);

/**
 * @property $id
 * @property $name
 */
class User extends ArrayObject
{
    /**
     * @ODTA\Data(field="id")
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    private $id;

    /**
     * @ODTA\Data(field="user_name", nullable=false, required=false)
     * @DTA\Validator(name="Type", options={"type":"string"})
     * @var string
     */
    private $userName;

    public function __get($name)
    {
        if (array_key_exists($name, $this)) {
            return $this[$name];
        }

        throw new OutOfBoundsException("Undefined index '$name'.");
    }

   public function __set($name, $value)
   {
       $this[$name] = $value;
   }

    public function setId($id)
    {
        $this['id'] = $id;
    }

    public function getId()
    {
        return $this['id'];
    }

    public function setUserName($name)
    {
        $this['userName'] = $name;
    }

    public function getUserName()
    {
        return $this['userName'];
    }

    public function hasId(): bool
    {
        return array_key_exists('id', $this);
    }

    public function hasUserName(): bool
    {
        return array_key_exists('userName', $this);
    }
}

$userArray = [
    'id' => 'foo',
    'user_name' => 'bar'
];

$user = new User();

$arr = [];
$errors = $dataTransfer->transferToTypedData($userArray, $user);
var_dump($errors);
$errors = $dataTransfer->transferFromTypedData($user, $arr);
//$array = [];
//$dataTransfer->transferFromTypedData($user, $array);
//var_dump($array);

var_dump($errors);
var_dump($arr);
//var_dump($user->hasId() ? $user->id : 'User has not id.');
//var_dump($user->hasName() ? $user->name : 'User has not name.');
//var_dump($user->hasId());
//var_dump($user->hasName());