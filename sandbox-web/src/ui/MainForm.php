<?php

namespace ui;

use framework\core\Event;
use framework\web\ui\UIAlert;
use framework\web\ui\UIButton;
use framework\web\ui\UICheckbox;
use framework\web\ui\UIHBox;
use framework\web\ui\UIListView;
use framework\web\ui\UITextField;
use framework\web\UIForm;

/**
 * Class MainForm
 * @package ui
 *
 * @property UIHBox $pane
 * @property UIButton $button
 * @property UIListView $quests
 * @property UITextField $input
 *
 */
class MainForm extends UIForm
{
    /**
     * @event button.click-left
     * @event input.keyUp-Enter
     * @param Event $e
     */
    public function doButtonClick(Event $e)
    {
        $text = $this->input->text;

        if ($text == '') {
            alert('Введите текст задания', ['type' => 'warning', 'title' => 'Сообщение']);
            return;
        }

        $line = new UICheckbox($text);

        $line->on('action', function (Event $e) {
            /** @var UICheckbox $checkbox */
            $checkbox = $e->sender;
            $checkbox->font->linethrough = $checkbox->selected;
        });

        $this->quests->add($line);
        $this->input->text = '';
        $this->clear->visible = true;
    }

    /**
     * @event clear.click
     */
    public function doClear()
    {
        $alert = new UIAlert('confirm');
        $alert->text = 'Вы уверены, что хотите удалить все записи?';
        $alert->title = 'Вопрос';
        $alert->buttons = ['no' => 'Нет, отмена', 'yes' => 'Да, удалить'];

        $alert->on('action-yes', function () {
            $this->quests->clear();

            $this->clear->visible = false;
        });

        $alert->show();
    }
}