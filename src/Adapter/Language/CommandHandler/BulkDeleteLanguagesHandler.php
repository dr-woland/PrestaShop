<?php
/**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

namespace PrestaShop\PrestaShop\Adapter\Language\CommandHandler;

use Context;
use Language;
use PrestaShop\PrestaShop\Core\Domain\Language\Command\BulkDeleteLanguagesCommand;
use PrestaShop\PrestaShop\Core\Domain\Language\CommandHandler\BulkDeleteLanguagesHandlerInterface;
use PrestaShop\PrestaShop\Core\Domain\Language\Exception\LanguageException;
use Shop;

/**
 * Deletes languages using legacy Language object model
 *
 * @internal
 */
final class BulkDeleteLanguagesHandler extends AbstractLanguageHandler implements BulkDeleteLanguagesHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle(BulkDeleteLanguagesCommand $command)
    {
        // language can only be modified in "ALL SHOPS" context
        Shop::setContext(Shop::CONTEXT_ALL);

        foreach ($command->getLanguageIds() as $languageId) {
            $language = $this->getLegacyLanguageObject($languageId);

            $this->assertIsNotDefaultLanguage($language);
            $this->assertIsNotInUseLanguage($language);

            if (false === $language->delete()) {
                throw new LanguageException(sprintf('Failed to delele language "%s"', $language->iso_code));
            }
        }
    }
}
