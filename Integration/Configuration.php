<?php

declare(strict_types=1);

namespace MauticPlugin\IrSmsBundle\Integration;


use Mautic\IntegrationsBundle\Helper\IntegrationsHelper;
use Mautic\CoreBundle\Exception\BadConfigurationException;


class Configuration
{
    /**
     * @var IntegrationsHelper
     */
    private $integrationHelper;


    /**
     * @var string
     */
    private $apiPlatform;

    /**
     * @var string
     */
    private $apiSecret;

    /**
     * @var string
     */
    private $apiKey = null;

    /**
     * @var string
     */
    private $apiNumber;

    /**
     * @var string
     */
    private $apiPattern;

    /**
     * @var string
     */
    private $campaignId;

    /**
     * Configuration constructor.
     */
    public function __construct(IntegrationsHelper $integrationHelper)
    {
        $this->integrationHelper = $integrationHelper;
    }

    /**
     * @return string
     *
     * @throws ConfigurationException
     */
    public function getCampaignId()
    {
        $this->setConfiguration();

        return $this->campaignId;
    }

    /**
     * @return string
     *
     * @throws ConfigurationException
     */
    public function getApiKey()
    {
        $this->setConfiguration();

        return $this->apiKey;
    }

    /**
     * @return string
     *
     * @throws ConfigurationException
     */
    public function getApiSecret()
    {
        $this->setConfiguration();

        return $this->apiSecret;
    }

    /**
     * @return string
     *
     * @throws ConfigurationException
     */
    public function getApiNumber()
    {
        $this->setConfiguration();

        return $this->apiNumber;
    }

    /**
     * @return string
     *
     * @throws ConfigurationException
     */
    public function getApiPattern()
    {
        $this->setConfiguration();

        return $this->apiPattern;
    }

    /**
     * @return string
     *
     * @throws ConfigurationException
     */
    public function getPlatform()
    {
        $this->setConfiguration();

        return $this->apiPlatform;
    }

    /**
     * @throws ConfigurationException
     */
    public function setConfiguration()
    {
        if (!is_null($this->apiKey)) {
            return;
        }

        $integration = $this->integrationHelper->getIntegration(IrSmsIntegration::NAME);
        $config = $integration->getIntegrationConfiguration();

        if (!$integration || !$config->getIsPublished()) {
            throw new BadConfigurationException();
        }

        $featureSettings = $config->getFeatureSettings();
        $this->apiKey = isset($featureSettings['irsms_api_key']) ? $featureSettings['irsms_api_key'] : null;
        if (is_null($this->apiKey)) {
            throw new BadConfigurationException();
        }

        $this->apiSecret = isset($featureSettings['irsms_api_secret']) ? $featureSettings['irsms_api_secret'] : null;
        $this->apiNumber = isset($featureSettings['irsms_api_number']) ? $featureSettings['irsms_api_number'] : null;
        $this->apiPattern = isset($featureSettings['irsms_api_pattern']) ? $featureSettings['irsms_api_pattern'] : null;
        $this->apiPlatform = isset($featureSettings['irsms_platform']) ? $featureSettings['irsms_platform'] : null;
    }
}
