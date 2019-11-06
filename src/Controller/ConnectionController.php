<?php

namespace Payplug\Bundle\PaymentBundle\Controller;

use Oro\Bundle\IntegrationBundle\Entity\Channel;
use Oro\Bundle\IntegrationBundle\Form\Type\ChannelType;
use Oro\Bundle\SecurityBundle\Annotation\CsrfProtection;
use Payplug\Bundle\PaymentBundle\Constant\PayplugSettingsConstant;
use Payplug\Bundle\PaymentBundle\Entity\PayplugSettings;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ConnectionController extends Controller
{
    /**
     * @Route("/login/{channelId}/", name="payplug_login")
     * @ParamConverter("channel", class="OroIntegrationBundle:Channel", options={"id" = "channelId"})
     * @Method("POST")
     * @CsrfProtection()
     *
     * @param Request      $request
     * @param Channel|null $channel
     *
     * @return JsonResponse
     *
     * @throws \InvalidArgumentException
     */
    public function loginAction(Request $request, Channel $channel = null): JsonResponse
    {
        $form = $this->createForm(
            ChannelType::class,
            $channel
        );
        $form->handleRequest($request);

        /** @var PayplugSettings $settings */
        $settings = $channel->getTransport();

        if (empty($request->get('oro_integration_channel_form')['transport']['password'])) {
            return new JsonResponse([
                'success' => false,
                'message' => $this->get('translator')->trans('payplug.settings.login.result.no_password.message')
            ]);
        }

        $apiKeys = $this->get('payplug.service.gateway')->authenticate(
            $settings->getLogin(),
            $request->get('oro_integration_channel_form')['transport']['password']
        );

        if (empty($apiKeys)) {
            return new JsonResponse([
                'success' => false,
                'message' => $this->get('translator')->trans('payplug.settings.login.result.error.message')
            ]);
        }

        $this->sendFlashMessage('success', 'payplug.settings.login.result.success.message');
        return new JsonResponse([
            'success' => true,
            'url' => $this->get('router')->generate('oro_integration_index'),
            'api_key_test' => $apiKeys['test'],
            'api_key_live' => $apiKeys['live'],
        ]);
    }

    /**
     * @Route("/logout/{channelId}/", name="payplug_logout")
     * @ParamConverter("channel", class="OroIntegrationBundle:Channel", options={"id" = "channelId"})
     * @Method("POST")
     * @CsrfProtection()
     *
     * @param Request      $request
     * @param Channel|null $channel
     *
     * @return JsonResponse
     *
     * @throws \InvalidArgumentException
     */
    public function logoutAction(Request $request, Channel $channel = null): JsonResponse
    {
        $form = $this->createForm(
            ChannelType::class,
            $channel
        );
        $form->handleRequest($request);

        /** @var PayplugSettings $settings */
        $settings = $channel->getTransport();

        $settings->setApiKeyLive(null);
        $settings->setApiKeyTest(null);
        $settings->setMode(PayplugSettingsConstant::MODE_TEST);
        $this->persistAndFlush($settings);

        $this->sendFlashMessage('success', 'payplug.settings.logout.result.success.message');
        return new JsonResponse([
            'success' => true,
            'disconnect' => true,
        ]);
    }

    private function sendFlashMessage(string $type, string $message): void
    {
        $this->get('session')->getFlashBag()->add(
            $type,
            $this->get('translator')->trans($message)
        );
    }

    private function persistAndFlush($entity): void
    {
        $this->getDoctrine()->getManager()->persist($entity);
        $this->getDoctrine()->getManager()->flush();
    }
}
