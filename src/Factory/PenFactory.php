<?php

namespace App\Factory;

use App\Entity\Pen;
use App\Repository\PenRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Pen>
 *
 * @method        Pen|Proxy                     create(array|callable $attributes = [])
 * @method static Pen|Proxy                     createOne(array $attributes = [])
 * @method static Pen|Proxy                     find(object|array|mixed $criteria)
 * @method static Pen|Proxy                     findOrCreate(array $attributes)
 * @method static Pen|Proxy                     first(string $sortedField = 'id')
 * @method static Pen|Proxy                     last(string $sortedField = 'id')
 * @method static Pen|Proxy                     random(array $attributes = [])
 * @method static Pen|Proxy                     randomOrCreate(array $attributes = [])
 * @method static PenRepository|RepositoryProxy repository()
 * @method static Pen[]|Proxy[]                 all()
 * @method static Pen[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Pen[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Pen[]|Proxy[]                 findBy(array $attributes)
 * @method static Pen[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Pen[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class PenFactory extends ModelFactory
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
        $capacity = self::faker()->randomDigit();
        $type = self::faker()->randomElement(['cage', 'aquarium', 'barrière']);
        $size = self::faker()->randomFloat(1, 1000, 3000);
        return [
            'capacity' => $capacity,
            'type' => $type,
            'size' => $size,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Pen $pen): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Pen::class;
    }
}
