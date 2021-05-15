<?php


use Nikservik\AdminSupport\Actions\Dialog\ListDialogs;
use Nikservik\AdminSupport\Actions\Dialog\SearchDialog;
use Nikservik\AdminSupport\Actions\Dialog\ShowDialog;
use Nikservik\AdminSupport\Actions\Notification\CreateNotification;
use Nikservik\AdminSupport\Actions\Notification\DeleteNotification;
use Nikservik\AdminSupport\Actions\Notification\ListNotifications;
use Nikservik\AdminSupport\Actions\Notification\UpdateNotification;
use Nikservik\AdminSupport\Actions\SupportMessage\CreateSupportMessage;
use Nikservik\AdminSupport\Actions\SupportMessage\DeleteSupportMessage;
use Nikservik\AdminSupport\Actions\SupportMessage\EditSupportMessage;
use Nikservik\AdminSupport\Actions\SupportMessage\UpdateSupportMessage;

//ListNotifications::route();
//CreateNotification::route();
//DeleteNotification::route();
//UpdateNotification::route();

CreateSupportMessage::route();
DeleteSupportMessage::route();
EditSupportMessage::route();
UpdateSupportMessage::route();

ShowDialog::route();
SearchDialog::route();
// регистрируется последним, потому что содержит необязательный параметр
ListDialogs::route();
