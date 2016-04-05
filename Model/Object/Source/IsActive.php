<?php
namespace Zero\Base\Model\Object\Source;

class IsActive implements \Magento\Framework\Data\OptionSourceInterface
{

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options[] = ['label' => '', 'value' => ''];
        $options[] = ['label' => __('Enable'), 'value' => $this::STATUS_ENABLED];
        $options[] = ['label' => __('Disable'), 'value' => $this::STATUS_DISABLED];

        return $options;
    }
}
