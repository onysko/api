<?php
//[PHPCOMPRESSOR(remove,start)]
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 22.03.16 at 15:46
 */
namespace samsoncms\api\generator;

use samsonphp\generator\Generator;

/**
 * Entity Query class generator.
 *
 * @package samsoncms\api\generator
 */
class Collection extends Generic
{
    /**
     * Query constructor.
     *
     * @param Generator $generator
     * @param           $metadata
     */
    public function __construct(Generator $generator, $metadata)
    {
        parent::__construct($generator, $metadata);

        $this->className .= 'Collection';
    }

    /**
     * Class uses generation part.
     *
     * @param \samsoncms\api\generator\metadata\Virtual $metadata Entity metadata
     */
    protected function createUses($metadata)
    {
        $this->generator
            ->newLine('use samsonframework\core\ViewInterface;')
            ->newLine('use samsonframework\orm\QueryInterface;')
            ->newLine('use samsonframework\orm\ArgumentInterface;')
            ->newLine('use samson\activerecord\dbQuery;')
            ->newLine();
    }

    /**
     * Class definition generation part.
     *
     * @param \samsoncms\api\generator\metadata\Virtual $metadata Entity metadata
     */
    protected function createDefinition($metadata)
    {
        $this->generator
            ->multiComment(array(
                'Class for rendering and querying and fetching "' . $metadata->entityRealName . '" instances from database',
                '@method ' . $metadata->entity . ' first();',
                '@method ' . $metadata->entity . '[] find();',
            ))
            ->defClass($this->className, $metadata->entity .'Query')
            ->newLine('use \\'.\samsoncms\api\Renderable::class.';')
        ->newLine();
    }

    /**
     * Class constructor generation part.
     *
     * @param \samsoncms\api\generator\metadata\Virtual $metadata Entity metadata
     */
    protected function createConstructor($metadata)
    {
        $class = "\n\t".'/**';
        $class .= "\n\t".' * @param ViewInterface $renderer Rendering instance';
        $class .= "\n\t" . ' * @param QueryInterface $query Database query instance';
        $class .= "\n\t".' * @param string $locale Localization identifier';
        $class .= "\n\t".' */';
        $class .= "\n\t" . 'public function __construct(ViewInterface $renderer, QueryInterface $query = null, $locale = null)';
        $class .= "\n\t".'{';
        $class .= "\n\t\t" . '$this->renderer = $renderer;';
        $class .= "\n\t\t" . 'parent::__construct(isset($query) ? $query : new dbQuery(), $locale);';
        $class .= "\n\t".'}';

        $this->generator->text($class);
    }
}
//[PHPCOMPRESSOR(remove,end)]