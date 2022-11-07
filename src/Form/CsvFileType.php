<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class CsvFileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('csv', FileType::class, [
                'label' => 'CSV файл с сотрудниками',
                'required' => true,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'text/csv',
                        ],
                        'mimeTypesMessage' => 'Пожалуйста, загрузите CSV файл',
                    ])
                ],
            ])
            ->add('upload', SubmitType::class, ['label' => 'Загрузить']);
    }
}