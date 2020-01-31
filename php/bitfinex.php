<?php

namespace ccxtpro;

// PLEASE DO NOT EDIT THIS FILE, IT IS GENERATED AND WILL BE OVERWRITTEN:
// https://github.com/ccxt/ccxt/blob/master/CONTRIBUTING.md#how-to-contribute-code

use Exception; // a common import
use \ccxt\ExchangeError;

class bitfinex extends \ccxt\bitfinex {

    use ClientTrait;

    public function describe () {
        return array_replace_recursive(parent::describe (), array(
            'has' => array(
                'watchTicker' => true,
                'watchTickers' => false,
                'watchOrderBook' => true,
            ),
            'urls' => array(
                'api' => array(
                    'ws' => array(
                        'public' => 'wss://api.bitfinex.com/ws/1',
                        'private' => 'wss://api.bitfinex.com/ws/1',
                    ),
                ),
            ),
        ));
    }

    public function watch_ticker ($symbol, $params = array ()) {
        $this->load_markets();
        $market = $this->market ($symbol);
        $marketId = $market['id'];
        $url = $this->urls['api']['ws']['public'];
        $channel = 'ticker';
        $request = array(
            'event' => 'subscribe',
            'channel' => $channel,
            'symbol' => $marketId,
        );
        $messageHash = $channel . ':' . $marketId;
        return $this->watch ($url, $messageHash, array_replace_recursive($request, $params), $messageHash);
    }

    public function handle_ticker ($client, $message, $subscription) {
        //
        //     array(
        //         2,             // 0 CHANNEL_ID integer Channel ID
        //         236.62,        // 1 BID float Price of $last highest bid
        //         9.0029,        // 2 BID_SIZE float Size of the $last highest bid
        //         236.88,        // 3 ASK float Price of $last lowest ask
        //         7.1138,        // 4 ASK_SIZE float Size of the $last lowest ask
        //         -1.02,         // 5 DAILY_CHANGE float Amount that the $last price has changed since yesterday
        //         0,             // 6 DAILY_CHANGE_PERC float Amount that the price has changed expressed in percentage terms
        //         236.52,        // 7 LAST_PRICE float Price of the $last trade.
        //         5191.36754297, // 8 VOLUME float Daily volume
        //         250.01,        // 9 HIGH float Daily high
        //         220.05,        // 10 LOW float Daily low
        //     )
        //
        $timestamp = $this->milliseconds ();
        $marketId = $this->safe_string($subscription, 'pair');
        $market = $this->markets_by_id[$marketId];
        $symbol = $market['symbol'];
        $channel = 'ticker';
        $messageHash = $channel . ':' . $marketId;
        $last = $this->safe_float($message, 7);
        $change = $this->safe_float($message, 5);
        $open = null;
        if (($last !== null) && ($change !== null)) {
            $open = $last - $change;
        }
        $result = array(
            'symbol' => $symbol,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601 ($timestamp),
            'high' => $this->safe_float($message, 9),
            'low' => $this->safe_float($message, 10),
            'bid' => $this->safe_float($message, 1),
            'bidVolume' => null,
            'ask' => $this->safe_float($message, 3),
            'askVolume' => null,
            'vwap' => null,
            'open' => $open,
            'close' => $last,
            'last' => $last,
            'previousClose' => null,
            'change' => $change,
            'percentage' => $this->safe_float($message, 6),
            'average' => null,
            'baseVolume' => $this->safe_float($message, 8),
            'quoteVolume' => null,
            'info' => $message,
        );
        $this->tickers[$symbol] = $result;
        $client->resolve ($result, $messageHash);
    }

    public function watch_order_book ($symbol, $limit = null, $params = array ()) {
        if ($limit !== null) {
            if (($limit !== 25) && ($limit !== 100)) {
                throw new ExchangeError($this->id . ' watchOrderBook $limit argument must be null, 25 or 100');
            }
        }
        $this->load_markets();
        $market = $this->market ($symbol);
        $marketId = $market['id'];
        $url = $this->urls['api']['ws']['public'];
        $channel = 'book';
        $request = array(
            'event' => 'subscribe',
            'channel' => $channel,
            'symbol' => $marketId,
            // 'prec' => 'P0', // string, level of price aggregation, 'P0', 'P1', 'P2', 'P3', 'P4', default P0
            // 'freq' => 'F0', // string, frequency of updates 'F0' = realtime, 'F1' = 2 seconds, default is 'F0'
            // 'len' => '25', // string, number of price points, '25', '100', default = '25'
        );
        if ($limit !== null) {
            $request['len'] = (string) $limit;
        }
        $messageHash = $channel . ':' . $marketId;
        $future = $this->watch ($url, $messageHash, array_replace_recursive($request, $params), $messageHash);
        return $this->after ($future, array($this, 'limit_order_book'), $symbol, $limit, $params);
    }

    public function limit_order_book ($orderbook, $symbol, $limit = null, $params = array ()) {
        return $orderbook->limit ($limit);
    }

    public function handle_order_book ($client, $message, $subscription) {
        //
        // first $message (snapshot)
        //
        //     array(
        //         18691, // $channel id
        //         array(
        //             array( 7364.8, 10, 4.354802 ), // price, count, size > 0 = bid
        //             array( 7364.7, 1, 0.00288831 ),
        //             array( 7364.3, 12, 0.048 ),
        //             array( 7364.9, 3, -0.42028976 ), // price, count, size < 0 = ask
        //             array( 7365, 1, -0.25 ),
        //             array( 7365.5, 1, -0.00371937 ),
        //         )
        //     )
        //
        // subsequent updates
        //
        //     array(
        //         30,     // $channel id
        //         9339.9, // price
        //         0,      // count
        //         -1,     // size > 0 = bid, size < 0 = ask
        //     )
        //
        $marketId = $this->safe_string($subscription, 'pair');
        $market = $this->markets_by_id[$marketId];
        $symbol = $market['symbol'];
        $channel = 'book';
        $messageHash = $channel . ':' . $marketId;
        // if it is an initial snapshot
        if (gettype($message[1]) === 'array' && count(array_filter(array_keys($message[1]), 'is_string')) == 0) {
            $limit = $this->safe_integer($subscription, 'len');
            $this->orderbooks[$symbol] = $this->counted_order_book (array(), $limit);
            $orderbook = $this->orderbooks[$symbol];
            $deltas = $message[1];
            for ($i = 0; $i < count($deltas); $i++) {
                $delta = $deltas[$i];
                $amount = ($delta[2] < 0) ? -$delta[2] : $delta[2];
                $side = ($delta[2] < 0) ? 'asks' : 'bids';
                $bookside = $orderbook[$side];
                $bookside->store ($delta[0], $amount, $delta[1]);
            }
            $client->resolve ($orderbook, $messageHash);
        } else {
            $orderbook = $this->orderbooks[$symbol];
            $amount = ($message[3] < 0) ? -$message[3] : $message[3];
            $side = ($message[3] < 0) ? 'asks' : 'bids';
            $bookside = $orderbook[$side];
            $bookside->store ($message[1], $amount, $message[2]);
            $client->resolve ($orderbook, $messageHash);
        }
    }

    public function handle_heartbeat ($client, $message) {
        //
        // every second (approx) if no other updates are sent
        //
        //     array( "$event" => "heartbeat" )
        //
        $event = $this->safe_string($message, 'event');
        $client->resolve ($message, $event);
    }

    public function handle_system_status ($client, $message) {
        //
        // todo => answer the question whether handleSystemStatus should be renamed
        // and unified as handleStatus for any usage pattern that
        // involves system status and maintenance updates
        //
        //     {
        //         event => 'info',
        //         version => 2,
        //         serverId => 'e293377e-7bb7-427e-b28c-5db045b2c1d1',
        //         platform => array( status => 1 ), // 1 for operative, 0 for maintenance
        //     }
        //
        return $message;
    }

    public function handle_subscription_status ($client, $message) {
        //
        //     {
        //         event => 'subscribed',
        //         channel => 'book',
        //         chanId => 67473,
        //         symbol => 'tBTCUSD',
        //         prec => 'P0',
        //         freq => 'F0',
        //         len => '25',
        //         pair => 'BTCUSD'
        //     }
        //
        $channelId = $this->safe_string($message, 'chanId');
        $client->subscriptions[$channelId] = $message;
        return $message;
    }

    public function sign_message ($client, $messageHash, $message, $params = array ()) {
        // todo => bitfinex signMessage not implemented yet
        return $message;
    }

    public function handle_message ($client, $message) {
        // var_dump (new Date (), $message);
        if (gettype($message) === 'array' && count(array_filter(array_keys($message), 'is_string')) == 0) {
            $channelId = $this->safe_string($message, 0);
            //
            //     array(
            //         1231,
            //         'hb',
            //     )
            //
            if ($message[1] === 'hb') {
                return $message; // skip heartbeats within $subscription channels for now
            }
            $subscription = $this->safe_value($client->subscriptions, $channelId, array());
            $channel = $this->safe_string($subscription, 'channel');
            $methods = array(
                'book' => array($this, 'handle_order_book'),
                // 'ohlc' => $this->handleOHLCV,
                'ticker' => array($this, 'handle_ticker'),
                // 'trade' => $this->handleTrades,
            );
            $method = $this->safe_value($methods, $channel);
            if ($method === null) {
                return $message;
            } else {
                return $method($client, $message, $subscription);
            }
        } else {
            // todo => add bitfinex handleErrorMessage
            //
            //     {
            //         $event => 'info',
            //         version => 2,
            //         serverId => 'e293377e-7bb7-427e-b28c-5db045b2c1d1',
            //         platform => array( status => 1 ), // 1 for operative, 0 for maintenance
            //     }
            //
            $event = $this->safe_string($message, 'event');
            if ($event !== null) {
                $methods = array(
                    'info' => array($this, 'handle_system_status'),
                    // 'book' => 'handleOrderBook',
                    'subscribed' => array($this, 'handle_subscription_status'),
                );
                $method = $this->safe_value($methods, $event);
                if ($method === null) {
                    return $message;
                } else {
                    return $method($client, $message);
                }
            }
        }
    }
}
