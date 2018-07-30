<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 7/28/18
 * Time: 7:15 PM
 */


use Voucher\Library\Utils;
use Voucher\Constants\Status;
use Carbon\Carbon;
use Phalcon\Mvc\Model\Query\Builder as QueryBuilder;

class Voucher extends BaseModel
{
    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::onConstruct();
        $this->setSource('vouchers');

        $this->hasOne(
            'user_id',
            User::class,
            'id'
        );

        $this->hasOne(
            'special_offer_id',
            SpecialOffer::class,
            'id'
        );
    }

    /**
     * @param $offerId
     * @param $userId
     * @param $expireDate
     * @param $expireInterval
     * @return mixed
     * @throws \Exception
     */
    public function generate($offerId, $userId, $expireDate, $expireInterval)
    {
        try {
            $code = new self();
            if (!SpecialOffer::exist($offerId)) {
                throw new \Exception('The special offer id supplied is invalid');
            }

            $toAdd = [
                'code' => Utils::generateRandomShortCode(8),
                'special_offer_id' => $offerId,
                'user_id' => $userId,
                'expiry_date' => $expireDate,
                'expire_interval' => $expireInterval
            ];

            $code->setArrayValueToField($toAdd)->save();
            return $code->refresh();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @return bool
     */
    public function hasExpired()
    {
        $now = Carbon::now();
        $expiryDate = new Carbon($this->expiry_date);
        return $expiryDate->lt($now);
    }

    /**
     * check if voucher code has been used
     * @return bool
     */
    public function isUsed()
    {
        return (bool)$this->is_used;
    }

    /**
     * @param $offerId
     * @param $expireDate
     * @param $expireInterval
     * @return mixed
     * @throws \Exception
     */
    public function generateForAllRecipient($offerId, $expireDate, $expireInterval)
    {
        try {
            $users = User::getByFields(['status' => Status::ACTIVE], 'AND');
            if (empty($users)) {
                return false;
            }

            foreach ($users as $user) {
                $this->generate($offerId, $user->id, $expireDate, $expireInterval);
            }

            return true;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function updateUsage()
    {
        $this->is_used = 1;
        $this->status = Status::DISABLED;
        $this->date_of_usage = Carbon::now()->toDateTimeString();
        $this->save();
    }

    public static function getAll($page = 1, $limit = 100)
    {
        $builder = new QueryBuilder();
        $builder
            ->from(['v' => Voucher::class])
            ->columns([
                'email' => 'u.email',
                'voucher_code' => 'v.code',
                'special_offer_name' => 's.name',
                'discount_percentage' => 's.discount',
                'date_of_usage' => 'v.date_of_usage',
                'is_used' => 'v.is_used',
                'expiry_date' => 'v.expiry_date',
            ])
            ->where('v.status != :status:', ['status' => Status::DISABLED]);

        $builder->innerJoin(SpecialOffer::class, 's.id = v.special_offer_id', 's')
            ->innerJoin(User::class, 'u.id = v.user_id', 'u');
        $offset = Utils::getOffset($page, $limit);

        return $builder->offset($offset)
            ->limit($limit)
            ->getQuery()->execute()->toArray();
    }
}