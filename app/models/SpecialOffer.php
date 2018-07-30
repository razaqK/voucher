<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 7/28/18
 * Time: 7:15 PM
 */

class SpecialOffer extends BaseModel
{
    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::onConstruct();
        $this->setSource('special_offers');
    }

    /**
     * @param $name
     * @param $discount
     * @return mixed
     * @throws \Exception
     */
    public function add($name, $discount)
    {
        try {
            $offer = new self();

            $toAdd = [
                'name' => strtolower($name),
                'discount' => $discount
            ];

            $offer->setArrayValueToField($toAdd)->save();
            return $offer->refresh();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param $offerId
     * @return \Phalcon\Mvc\Model
     */
    public static function exist($offerId)
    {
        return self::getById($offerId);
    }
}