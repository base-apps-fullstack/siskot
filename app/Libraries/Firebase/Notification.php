<?php

namespace App\Libraries\Firebase;

use FCM;
use LaravelFCM\Message\Topics;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Response\GroupResponse;
use LaravelFCM\Response\TopicResponse;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Response\DownstreamResponse;
use LaravelFCM\Message\PayloadNotificationBuilder;

class Notification
{

    /**
     * Notification builder
     *
     * @var PayloadNotificationBuilder
     */
    protected $notification = null;

    /**
     * Notification options
     *
     * @var OptionsBuilder
     */
    protected $options = null;

    /**
     * Notification payload
     *
     * @var PayloadDataBuilder
     */
    protected $payload = null;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->options();
    }

    /**
     * Notification options
     *
     * @return $this
     */
    public function options(): self
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $this->options = $optionBuilder->build();

        return $this;
    }

    /**
     * Create notification builder
     *
     * @param array $notification
     * @return $this
     */
    public function create(array $notification): self
    {
        $notificationBuilder = new PayloadNotificationBuilder($notification['title']);
        $notificationBuilder->setBody($notification['content'])->setSound('default');

        $this->notification = $notificationBuilder->build();

        return $this;
    }

    /**
     * Notification payload
     *
     * @param array $payload
     * @return $this
     */
    public function payload(array $payload): self
    {
        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData($payload);

        $this->payload = $dataBuilder->build();

        return $this;
    }

    /**
     * Send notification to user
     *
     * @param string|array $token
     * @return DownstreamResponse|null
     */
    public function sendTo($token): ?DownstreamResponse
    {
        return FCM::sendTo($token, $this->options, $this->notification, $this->payload);
    }

    // TOPIC SECTION

    /**
     * Send notification to topic
     *
     * @param string|Topics $topicName
     * @return TopicResponse
     */
    public function sentToTopic($topicName): TopicResponse
    {
        if ($topicName instanceof Topics) :
            $topic = $topicName;
        else :
            $topic = new Topics();
            $topic->topic($topicName);
        endif;

        return FCM::sendToTopic($topic, null, $this->notification, null);
    }

    // GROUP SECTION

    /**
     * Send notification to group
     *
     * @param array $notificationKey
     * @return GroupResponse
     */
    public function sendToGroup(array $notificationKey): GroupResponse
    {
        return FCM::sendToGroup($notificationKey, null, $this->notification, null);
    }

    /**
     * Create group
     *
     * @param string $groupName
     * @param array $groupMembers
     * @return string|null group notification key
     */
    public static function createGroup(string $groupName, array $groupMembers): ?string
    {
        return FCMGroup::createGroup($groupName, $groupMembers);
    }

    /**
     * Add user key to group
     *
     * @param string $groupName
     * @param string $groupNotificationKey
     * @param array $groupMembers
     * @return string|null group notification key
     */
    public static function addGroupMember(string $groupName, string $groupNotificationKey, array $groupMembers): ?string
    {
        return FCMGroup::addToGroup($groupName, $groupNotificationKey, $groupMembers);
    }

    /**
     * Remove user key from group
     *
     * @param string $groupName
     * @param string $groupNotificationKey
     * @param array $groupMembers
     * @return string|null group notification key
     */
    public static function removeGroupMember(string $groupName, string $groupNotificationKey, array $groupMembers): ?string
    {
        return FCMGroup::removeFromGroup($groupName, $groupNotificationKey, $groupMembers);
    }
}
