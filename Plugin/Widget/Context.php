<?php

namespace Zero\Base\Plugin\Widget;


class Context
{
    public function afterGetButtonList(
        \Magento\Backend\Block\Widget\Context $subject,
        $buttonList
    )
    {
        if($subject->getRequest()->getFullActionName() == 'cms_page_edit'){
            $buttonList->add(
                'custom_button',
                [
                    'label' => __('Custom Button'),
                        'onclick' => "setLocation('window.location.href')",
                        'class' => 'ship'
                    ]
                );
        }

        return $buttonList;
    }
}
