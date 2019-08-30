<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Base normalizer class.
 */
abstract class BaseNormalizer extends AbstractNormalizer {

  use ClassNameSupportNormalizerTrait;

  /**
   * {@inheritdoc}
   */
  public function denormalize($data, $class, $format = NULL, array $context = []) {
    throw new \RuntimeException("Method not implemented.");
  }

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = []) {
    throw new \RuntimeException("Method not implemented.");
  }

}
