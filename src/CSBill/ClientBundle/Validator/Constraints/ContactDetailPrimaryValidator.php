<?php

namespace CSBill\ClientBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use CS\CoreBundle\Util\ArrayUtil;

class ContactDetailPrimaryValidator extends ConstraintValidator implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $types = $this->getRequiredTypes();

        foreach ($value as $val) {
            /** @var \CSBill\ClientBundle\Entity\ContactDetail $val */
            $type = (string) $val->getType();

            if (in_array($type, $types)) {
                if ((bool) $val->isPrimary()) {
                    unset($types[array_search($type, $types)]);
                }
            }
        }

        unset($type);

        if (!empty($types)) {
            foreach ($types as $type) {
                $this->context->addViolation('This contact should have at least one primary ' . $type, array());
            }
        }
    }

    /**
     * @return array
     */
    protected function getRequiredTypes()
    {
        $em = $this->container->get('doctrine')->getManager();

        $types = $em->getRepository('CSBillClientBundle:ContactType')->findByRequired(1);

        return Arrayutil::column($types, 'name');
    }
}
