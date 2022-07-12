<?php

namespace Plugin\NewsPageSelfReliance42\Form\Extension;

use Eccube\Form\Type\Admin\NewsType;
use Eccube\Common\EccubeConfig;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Validator\Constraints as Assert;

class NpsrNewsTypeExtension extends AbstractTypeExtension
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    public function __construct(EccubeConfig $eccubeConfig)
    {
        $this->eccubeConfig = $eccubeConfig;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $options = $builder->get('description')->getOptions();
        $options['constraints'] = [
            new Assert\Length(['max' => $this->eccubeConfig['eccube_lltext_len']]),
        ];

        $builder->add('description', TextareaType::class, $options);
        $builder->add('np_thumbnail_data', FileType::class, [
            'label' => 'サムネイル画像',
            'required' => false,
            'eccube_form_options' => [
                'auto_render' => true,
            ],
            'mapped' => false,
        ]);

        $builder->add('np_thumbnail_url', HiddenType::class, [
            'required' => false,
            'eccube_form_options' => [
                'auto_render' => true,
            ],
        ]);

        $builder
            ->add('npseo_title', TextType::class, [
                'label' => '[SEO]タイトル',
                'required' => false,
                'eccube_form_options' => [
                    'auto_render' => true,
                ],
                'constraints' => [
                    new Assert\Length(['max' => 60]),
                ],
        ]);
        $builder
            ->add('npseo_description', TextareaType::class, [
                'label' => '[SEO]ディスクリプション',
                'required' => false,
                'eccube_form_options' => [
                    'auto_render' => true,
                ],
                'constraints' => [
                    new Assert\Length(['max' => 320]),
                ],
        ]);
        
        $builder
            ->add('npseo_robots', ChoiceType::class, [
                'label' => '[SEO]検索エンジンにインデックス',
                'choices' => [
                    'する(index,follow)' => "index,follow",
                    'しない(noindex,nofollow)' => "noindex,nofollow",
                ],
                'eccube_form_options' => [
                    'auto_render' => true,
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
        ]);

    }

    public function getExtendedType()
    {
        return NewsType::class;
    }

    /**
    * Return the class of the type being extended.
    */
    public static function getExtendedTypes(): iterable
    {
        yield NewsType::class;
    }

}
