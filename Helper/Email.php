<?php
namespace Zero\Base\Helper;

class Email extends \Magento\Framework\App\Helper\AbstractHelper
{
    private $_transportBuilder;
    private $_messageManager;
    private $_storeManager;

    function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
    )
    {
        parent::__construct($context);
        $this->_transportBuilder = $transportBuilder;
        $this->_messageManager = $messageManager;
        $this->_storeManager = $storeManager;
    }

    /**
     * Généric function to send simple mail
     * @param string | array $to mail and/or name
     * @param string $subject
     * @param string $message content of mail
     * @param array $parameters
     * @return boolean
     */
    public function sendGenericEmail($to, $subject, $message, $parameters = null)
    {
        // Formating params
        $params = "";
        if ($parameters) {
            foreach ($parameters as $name => $p) {
                $params .= "<br>" . $name . ' : ' . $p;
            }
        }

        // Formating TO
        if (is_array($to)) {
           $to_name = $to['name'];
           $to_email = $to['email'];
        } else {
           $to_name = $to;
           $to_email = $to;
        }

        // Get current store
        $currentStore = $this->_storeManager->getStore();

        $transport = $this->_transportBuilder->setTemplateIdentifier(
            "zero_base_generic_email"
        )->setTemplateOptions(
            [
                'area' => \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE,
                'store' => $currentStore['store_id']
            ]
        )->setTemplateVars(
            [
                'subject' => $subject,
                'message'=> $message,
                'params' => $params,
            ]
        )->setFrom(
            'general'
        )->addTo(
            $to_email,
            $to_name
        )->addBcc(
            ''
        )->getTransport();

        try {
            $transport->sendMessage();
        } catch (\Exception $e) {
            $this->_messageManager->addError($e->getMessage());
        }
    }
}
