<?php

namespace App\Observers;

use App\Models\CustomField;
use App\Traits\Uploadable;

class CustomFieldObserver
{
    use Uploadable;

    /**
     * Handle the custom field "created" event.
     *
     * @param  \App\Models\CustomField  $customField
     * @return void
     */
    public function created(CustomField $customField)
    {
        //
    }

    /**
     * Handle the custom field "updated" event.
     *
     * @param  \App\Models\CustomField  $customField
     * @return void
     */
    public function updated(CustomField $customField)
    {
        //
    }

    /**
     * Handle the custom field "deleted" event.
     *
     * @param  \App\Models\CustomField  $customField
     * @return void
     */
    public function deleted(CustomField $customField)
    {
        if ($customField->meta->type === 'image' || $customField->meta->type === 'file') {
            $this->fileDelete($customField->value);
        }
    }

    /**
     * Handle the custom field "restored" event.
     *
     * @param  \App\Models\CustomField  $customField
     * @return void
     */
    public function restored(CustomField $customField)
    {
        //
    }

    /**
     * Handle the custom field "force deleted" event.
     *
     * @param  \App\Models\CustomField  $customField
     * @return void
     */
    public function forceDeleted(CustomField $customField)
    {
        //
    }
}
