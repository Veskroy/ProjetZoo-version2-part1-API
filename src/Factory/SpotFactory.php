<?php

namespace App\Factory;

use App\Entity\Spot;
use App\Repository\SpotRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Spot>
 *
 * @method        Spot|Proxy                     create(array|callable $attributes = [])
 * @method static Spot|Proxy                     createOne(array $attributes = [])
 * @method static Spot|Proxy                     find(object|array|mixed $criteria)
 * @method static Spot|Proxy                     findOrCreate(array $attributes)
 * @method static Spot|Proxy                     first(string $sortedField = 'id')
 * @method static Spot|Proxy                     last(string $sortedField = 'id')
 * @method static Spot|Proxy                     random(array $attributes = [])
 * @method static Spot|Proxy                     randomOrCreate(array $attributes = [])
 * @method static SpotRepository|RepositoryProxy repository()
 * @method static Spot[]|Proxy[]                 all()
 * @method static Spot[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Spot[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Spot[]|Proxy[]                 findBy(array $attributes)
 * @method static Spot[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Spot[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class SpotFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'name' => mb_convert_case(self::faker()->word(), MB_CASE_TITLE, 'UTF-8'),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Spot $spot): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Spot::class;
    }
}
