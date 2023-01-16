/**
 * 2007-2023 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2023 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 *
 * Don't forget to prefix your containers with your own identifier
 * to avoid any conflicts with others containers.
 */

document.addEventListener("DOMContentLoaded", (_) => {
    // * Make container floating button
    const containerWhatsappFAB = document.createElement("div");
    containerWhatsappFAB.classList.add("container-whatsapp-button");
    containerWhatsappFAB.style.width = `${WHATSAPPBUTTON_WIDTH ?? 80}px`;
    containerWhatsappFAB.style.height = `${WHATSAPPBUTTON_HEIGHT ?? 80}px`;
    containerWhatsappFAB.style.right = `${WHATSAPPBUTTON_RIGHT ?? 15}px`;
    containerWhatsappFAB.style.bottom = `${WHATSAPPBUTTON_BOTTOM ?? 15}px`;


    // * Make whatsapp button
    const whatsappButton = document.createElement("lottie-player");
    whatsappButton.src = "https://assets3.lottiefiles.com/private_files/lf30_qnpfavmd.json"
    whatsappButton.setAttribute("speed", "1");
    whatsappButton.setAttribute("loop", "true");
    whatsappButton.setAttribute("autoplay", "true");

    // * Add event on click button
    containerWhatsappFAB.addEventListener("click", onClick);


    // * Add button in body
    containerWhatsappFAB.appendChild(whatsappButton);
    document.body.appendChild(containerWhatsappFAB);
});


function onClick(_) {
    let link = WHATSAPPBUTTON_LINK;
    if (link !== undefined && link !== "") {
        return window.open(link, '_blank');
    }


    let phone = WHATSAPPBUTTON_PHONE;
    if (phone !== undefined && phone !== "") {
        let countryCode = WHATSAPPBUTTON_COUNTRYCODE ?? 57;
        let message = WHATSAPPBUTTON_MESSAGE ?? "";

        let link = `https://wa.me/${countryCode}${phone}?text=${message}`;

        return window.open(link, '_blank');
    }
}