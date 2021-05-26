<?php


use Nikservik\AdminSupport\Actions\Dialog\CloseDialog;
use Nikservik\AdminSupport\Actions\Dialog\ListDialogs;
use Nikservik\AdminSupport\Actions\Dialog\SearchDialog;
use Nikservik\AdminSupport\Actions\Dialog\ShowDialog;
use Nikservik\AdminSupport\Actions\SupportMessage\CreateSupportMessage;
use Nikservik\AdminSupport\Actions\SupportMessage\DeleteSupportMessage;
use Nikservik\AdminSupport\Actions\SupportMessage\EditSupportMessage;
use Nikservik\AdminSupport\Actions\SupportMessage\UpdateSupportMessage;


CreateSupportMessage::route();
DeleteSupportMessage::route();
EditSupportMessage::route();
UpdateSupportMessage::route();

ShowDialog::route();
CloseDialog::route();
SearchDialog::route();
// регистрируется последним, потому что содержит необязательный параметр
ListDialogs::route();
