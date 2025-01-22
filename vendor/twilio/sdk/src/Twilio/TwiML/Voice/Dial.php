<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\TwiML\Voice;

use Twilio\TwiML\TwiML;

class Dial extends TwiML {
    /**
     * Dial constructor.
     *
     * @param string $number Phone number to dial
     * @param array $attributes Optional attributes
     */
    public function __construct($number = null, $attributes = []) {
        parent::__construct('Dial', $number, $attributes);
    }

    /**
     * Add Client child.
     *
     * @param string $identity Client identity
     * @param array $attributes Optional attributes
     * @return Client Child element.
     */
    public function client($identity = null, $attributes = []): Client {
        return $this->nest(new Client($identity, $attributes));
    }

    /**
     * Add Conference child.
     *
     * @param string $name Conference name
     * @param array $attributes Optional attributes
     * @return Conference Child element.
     */
    public function conference($name, $attributes = []): Conference {
        return $this->nest(new Conference($name, $attributes));
    }

    /**
     * Add Number child.
     *
     * @param string $phoneNumber Phone Number to dial
     * @param array $attributes Optional attributes
     * @return Number Child element.
     */
    public function number($phoneNumber, $attributes = []): Number {
        return $this->nest(new Number($phoneNumber, $attributes));
    }

    /**
     * Add Queue child.
     *
     * @param string $name Queue name
     * @param array $attributes Optional attributes
     * @return Queue Child element.
     */
    public function queue($name, $attributes = []): Queue {
        return $this->nest(new Queue($name, $attributes));
    }

    /**
     * Add Sim child.
     *
     * @param string $simSid SIM SID
     * @return Sim Child element.
     */
    public function sim($simSid): Sim {
        return $this->nest(new Sim($simSid));
    }

    /**
     * Add Sip child.
     *
     * @param string $sipUrl SIP URL
     * @param array $attributes Optional attributes
     * @return Sip Child element.
     */
    public function sip($sipUrl, $attributes = []): Sip {
        return $this->nest(new Sip($sipUrl, $attributes));
    }

    /**
     * Add Application child.
     *
     * @param string $applicationSid Application sid
     * @param array $attributes Optional attributes
     * @return Application Child element.
     */
    public function application($applicationSid = null, $attributes = []): Application {
        return $this->nest(new Application($applicationSid, $attributes));
    }

    /**
     * Add Action attribute.
     *
     * @param string $action Action URL
     */
    public function setAction($action): self {
        return $this->setAttribute('action', $action);
    }

    /**
     * Add Method attribute.
     *
     * @param string $method Action URL method
     */
    public function setMethod($method): self {
        return $this->setAttribute('method', $method);
    }

    /**
     * Add Timeout attribute.
     *
     * @param int $timeout Time to wait for answer
     */
    public function setTimeout($timeout): self {
        return $this->setAttribute('timeout', $timeout);
    }

    /**
     * Add HangupOnStar attribute.
     *
     * @param bool $hangupOnStar Hangup call on star press
     */
    public function setHangupOnStar($hangupOnStar): self {
        return $this->setAttribute('hangupOnStar', $hangupOnStar);
    }

    /**
     * Add TimeLimit attribute.
     *
     * @param int $timeLimit Max time length
     */
    public function setTimeLimit($timeLimit): self {
        return $this->setAttribute('timeLimit', $timeLimit);
    }

    /**
     * Add CallerId attribute.
     *
     * @param string $callerId Caller ID to display
     */
    public function setCallerId($callerId): self {
        return $this->setAttribute('callerId', $callerId);
    }

    /**
     * Add Record attribute.
     *
     * @param string $record Record the call
     */
    public function setRecord($record): self {
        return $this->setAttribute('record', $record);
    }

    /**
     * Add Trim attribute.
     *
     * @param string $trim Trim the recording
     */
    public function setTrim($trim): self {
        return $this->setAttribute('trim', $trim);
    }

    /**
     * Add RecordingStatusCallback attribute.
     *
     * @param string $recordingStatusCallback Recording status callback URL
     */
    public function setRecordingStatusCallback($recordingStatusCallback): self {
        return $this->setAttribute('recordingStatusCallback', $recordingStatusCallback);
    }

    /**
     * Add RecordingStatusCallbackMethod attribute.
     *
     * @param string $recordingStatusCallbackMethod Recording status callback URL
     *                                              method
     */
    public function setRecordingStatusCallbackMethod($recordingStatusCallbackMethod): self {
        return $this->setAttribute('recordingStatusCallbackMethod', $recordingStatusCallbackMethod);
    }

    /**
     * Add RecordingStatusCallbackEvent attribute.
     *
     * @param string[] $recordingStatusCallbackEvent Recording status callback
     *                                               events
     */
    public function setRecordingStatusCallbackEvent($recordingStatusCallbackEvent): self {
        return $this->setAttribute('recordingStatusCallbackEvent', $recordingStatusCallbackEvent);
    }

    /**
     * Add AnswerOnBridge attribute.
     *
     * @param bool $answerOnBridge Preserve the ringing behavior of the inbound
     *                             call until the Dialed call picks up
     */
    public function setAnswerOnBridge($answerOnBridge): self {
        return $this->setAttribute('answerOnBridge', $answerOnBridge);
    }

    /**
     * Add RingTone attribute.
     *
     * @param string $ringTone Ringtone allows you to override the ringback tone
     *                         that Twilio will play back to the caller while
     *                         executing the Dial
     */
    public function setRingTone($ringTone): self {
        return $this->setAttribute('ringTone', $ringTone);
    }

    /**
     * Add RecordingTrack attribute.
     *
     * @param string $recordingTrack To indicate which audio track should be
     *                               recorded
     */
    public function setRecordingTrack($recordingTrack): self {
        return $this->setAttribute('recordingTrack', $recordingTrack);
    }

    /**
     * Add Sequential attribute.
     *
     * @param bool $sequential Used to determine if child TwiML nouns should be
     *                         dialed in order, one after the other (sequential) or
     *                         dial all at once (parallel). Default is false,
     *                         parallel
     */
    public function setSequential($sequential): self {
        return $this->setAttribute('sequential', $sequential);
    }

    /**
     * Add ReferUrl attribute.
     *
     * @param string $referUrl Webhook that will receive future SIP REFER requests
     */
    public function setReferUrl($referUrl): self {
        return $this->setAttribute('referUrl', $referUrl);
    }

    /**
     * Add ReferMethod attribute.
     *
     * @param string $referMethod The HTTP method to use for the refer Webhook
     */
    public function setReferMethod($referMethod): self {
        return $this->setAttribute('referMethod', $referMethod);
    }

    /**
     * Add Events attribute.
     *
     * @param string $events Subscription to events
     */
    public function setEvents($events): self {
        return $this->setAttribute('events', $events);
    }
}