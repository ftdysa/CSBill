<?php

/*
 * This file is part of the CSBill package.
 *
 * (c) Pierre du Plessis <info@customscripts.co.za>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CSBill\ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use CSBill\ClientBundle\Form\Type\ContactDetailType;

class Contact extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstname');
        $builder->add('lastname');
        $builder->add('details', new ContactDetailType, array(
                                                              'type' => new ContactDetail,
                                                              'allow_add' => true,
                                                              'allow_delete' => true,
                                                              'by_reference' => false,
                                                              'label' => 'contact_details'
                                                             ));
    }

    public function getName()
    {
        return 'contact';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'CSBill\ClientBundle\Entity\Contact',
                'csrf_protection'=> false
        ));
    }
}
