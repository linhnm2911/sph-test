<?php

namespace Drupal\jp_product\Plugin\Field\FieldFormatter;

use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Plugin implementation of the 'link' formatter.
 *
 * @FieldFormatter(
 *   id = "jp_link_qr_code_formatter",
 *   label = @Translation("QR Code"),
 *   field_types = {
 *     "link"
 *   }
 * )
 */
class LinkQrCodeFieldFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'label' => 'Scan here on your mobile',
      'description' => 'To purchase this product on our app to avail exclusive app-only',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $settings = $this->getSettings();
    return [
      'label' => [
        '#type' => 'textfield',
        '#default_value' => $settings['label'] ?? '',
      ],
      'description' => [
        '#type' => 'textarea',
        '#default_value' => $settings['description'] ?? '',
      ],
    ] + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $settings = $this->getSettings();

    if (!empty($settings['label'])) {
      $summary[] = $this->t('Label: @label', ['@label' => $settings['label']]);
    }

    if (!empty($settings['description'])) {
      $summary[] = $this->t('Description: @description', ['@description' => $settings['description']]);
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta] = $this->viewValue($item);
    }

    return $elements;
  }

  /**
   * Generate the output appropriate for one field item.
   *
   * @param \Drupal\Core\Field\FieldItemInterface $item
   *   One field item.
   *
   * @return array
   *   The link QR build.
   */
  protected function viewValue(FieldItemInterface $item) {
    if (!$item->isEmpty()) {
      $url = $item->getValue()['uri'];
      if (UrlHelper::isValid($url)) {
        if (!UrlHelper::isExternal($url)) {
          $url = Url::fromUri($url, ['absolute' => TRUE])->toString();
        }

        // Render QR code.
        $renderer = new ImageRenderer(
          new RendererStyle(200),
          new ImagickImageBackEnd()
        );
        $writer = new Writer($renderer);

        return [
          '#theme' => 'link_qr_code',
          '#qr_code' => base64_encode($writer->writeString($url)),
          '#label' => $this->getSetting('label'),
          '#description' => $this->getSetting('description'),
        ];

      }
    }
  }

}
