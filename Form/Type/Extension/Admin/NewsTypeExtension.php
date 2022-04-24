<?php

namespace Plugin\NewsPages\Form\Type\Extension\Admin;

use Eccube\Form\Type\Admin\NewsType;

use Eccube\Common\EccubeConfig;

use Symfony\Component\Form\AbstractTypeExtension;
// use Symfony\Component\Form\Extension\Core\Type\FileType;

// use Symfony\Component\Form\AbstractTypeExtension;
// use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints as Assert;


class NewsTypeExtension extends AbstractTypeExtension
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    public function __construct(EccubeConfig $eccubeConfig)
    {
        $this->eccubeConfig = $eccubeConfig;
    }

    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = $builder->get('description')->getOptions();

        $options['constraints'] = [
            new Assert\Length(['max' => $this->eccubeConfig['eccube_lltext_len']]),
        ];

        $builder->add('description', TextareaType::class, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return NewsType::class;
    }
    
    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes(): iterable
    {
        yield NewsType::class;
    }
}

