<?php
/**
 * @file
 * Contains \Drupal\qrcode_demo\Plugin\Block\QrlinkBlock.
 */
namespace Drupal\qrcode_demo\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;
use chillerlan\QRCode\{QRCode, QROptions};
use Drupal\block\Entity\Block;

require 'vendor/autoload.php';

/**
 * Provides a 'QR Link' block.
 *
 * @Block(
 *   id = "qr_link_block",
 *   admin_label = @Translation("QR Link block"),
 *   category = @Translation("Display QR code for product purchase link")
 * )
 */
class QrlinkBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
		$node = \Drupal::routeMatch()->getParameter('node');
		
		if ($node instanceof \Drupal\node\NodeInterface) {
			if ($node->hasField('field_app_purchase_link')) {
				$getLinkVal = $node->get('field_app_purchase_link')->getValue();
				$appLink = Url::fromUri($node->field_app_purchase_link[0]->uri);
				$Qrlink = $appLink->toString();
				$options = new QROptions([
					'version'      => 3,
					'outputType'   => QRCode::OUTPUT_STRING_TEXT,
					'eccLevel'     => QRCode::ECC_L,
				]);
				$htmlContent = '<div class="link-qr" style="font-size:5px;"><pre>'.(new QRCode($options))->render($Qrlink).'</pre></div>';

				return array(
				  '#type' => 'markup',
				  '#markup' => $htmlContent,
				);
			}
		}
		
		$block = Block::load('qr_link_block');
		$block->setRegion('sidebar-first');
		$block->save();
		
  }
}