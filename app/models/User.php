<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 7/28/18
 * Time: 7:14 PM
 */

use Voucher\Library\Utils;
use Phalcon\Mvc\Model\Query\Builder as QueryBuilder;
use Voucher\Constants\Status;

class User extends BaseModel
{
    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::onConstruct();
        $this->setSource('users');
    }

    /**
     * @param $name
     * @param $email
     * @return mixed
     * @throws \Exception
     */
    public function add($name, $email)
    {
        try {
            $user = new self();
            $checkExistence = self::getByField($email, 'email');
            if ($checkExistence) {
                return false;
            }
            $toAdd = [
                'name' => strtolower($name),
                'email' => $email,
                'uuid' => Utils::generateRandomShortCode(10)
            ];

            $user->setArrayValueToField($toAdd)->save();
            return $user->refresh();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getInfo()
    {
        $builder = new QueryBuilder();
        $builder
            ->from(['v' => Voucher::class])
            ->columns([
                'user_id' => 'v.user_id',
                'voucher_code' => 'v.code',
                'special_offer_name' => 's.name'
            ])->where('v.user_id = :user_id:', ['user_id' => $this->id])
            ->andWhere('v.status != :status:', ['status' => Status::DISABLED]);

        return $builder->innerJoin(SpecialOffer::class, 's.id = v.special_offer_id', 's')
            ->getQuery()->execute()->toArray();
    }
}