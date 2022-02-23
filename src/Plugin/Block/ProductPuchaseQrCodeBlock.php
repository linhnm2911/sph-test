<?php

namespace Drupal\jp_product\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Product Puchase QrCode Block' block.
 *
 * @Block(
 *  id = "jp_product_puchase_qr_code",
 *  admin_label = @Translation("Product Purchase"),
 * )
 */
class ProductPuchaseQrCodeBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\Core\Routing\CurrentRouteMatch definition.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  protected $currentRouteMatch;

  /**
   * The node product.
   *
   * @var \Drupal\node\NodeInterface|null
   */
  private $currentProduct;

  /**
   * {@inheritDoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CurrentRouteMatch $current_route) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentRouteMatch = $current_route;
    $this->currentProduct = $current_route->getParameter('node');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_route_match')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    if (!$this->currentProduct instanceof NodeInterface || $this->currentProduct->bundle() !== 'product') {
      return [];
    }
    return $this->currentProduct->get('field_link_app_purchase')->view('qr_code');
  }

  /**
   * {@inheritDoc}
   */
  public function getCacheContexts() {
    return Cache::mergeContexts(parent::getCacheContexts(), ['route']);
  }

  /**
   * {@inheritDoc}
   */
  public function getCacheTags() {
    if ($this->currentProduct instanceof NodeInterface) {
      return Cache::mergeTags(parent::getCacheTags(), $this->currentProduct->getCacheTags());
    }
    else {
      return parent::getCacheTags();
    }
  }

}
