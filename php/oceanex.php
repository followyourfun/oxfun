<?php

namespace ccxt;

// PLEASE DO NOT EDIT THIS FILE, IT IS GENERATED AND WILL BE OVERWRITTEN:
// https://github.com/ccxt/ccxt/blob/master/CONTRIBUTING.md#how-to-contribute-code

use Exception; // a common import
use \ccxt\ExchangeError;
use \ccxt\ArgumentsRequired;
use \ccxt\OrderNotFound;

class oceanex extends Exchange {

    public function describe() {
        return $this->deep_extend(parent::describe (), array(
            'id' => 'oceanex',
            'name' => 'OceanEx',
            'countries' => array( 'BS' ), // Bahamas
            'version' => 'v1',
            'rateLimit' => 3000,
            'urls' => array(
                'logo' => 'https://user-images.githubusercontent.com/1294454/58385970-794e2d80-8001-11e9-889c-0567cd79b78e.jpg',
                'api' => 'https://api.oceanex.pro',
                'www' => 'https://www.oceanex.pro.com',
                'doc' => 'https://api.oceanex.pro/doc/v1',
                'referral' => 'https://oceanex.pro/signup?referral=VE24QX',
            ),
            'has' => array(
                'CORS' => null,
                'spot' => true,
                'margin' => false,
                'swap' => null, // has but unimplemented
                'future' => null,
                'option' => null,
                'cancelAllOrders' => true,
                'cancelOrder' => true,
                'cancelOrders' => true,
                'createMarketOrder' => true,
                'createOrder' => true,
                'fetchBalance' => true,
                'fetchBorrowRate' => false,
                'fetchBorrowRateHistories' => false,
                'fetchBorrowRateHistory' => false,
                'fetchBorrowRates' => false,
                'fetchBorrowRatesPerSymbol' => false,
                'fetchClosedOrders' => true,
                'fetchMarkets' => true,
                'fetchOHLCV' => true,
                'fetchOpenOrders' => true,
                'fetchOrder' => true,
                'fetchOrderBook' => true,
                'fetchOrderBooks' => true,
                'fetchOrders' => true,
                'fetchTicker' => true,
                'fetchTickers' => true,
                'fetchTime' => true,
                'fetchTrades' => true,
                'fetchTradingFee' => false,
                'fetchTradingFees' => true,
                'fetchTradingLimits' => null,
                'fetchTransactionFees' => null,
            ),
            'timeframes' => array(
                '1m' => '1',
                '5m' => '5',
                '15m' => '15',
                '30m' => '30',
                '1h' => '60',
                '2h' => '120',
                '4h' => '240',
                '6h' => '360',
                '12h' => '720',
                '1d' => '1440',
                '3d' => '4320',
                '1w' => '10080',
            ),
            'api' => array(
                'public' => array(
                    'get' => array(
                        'markets',
                        'tickers/{pair}',
                        'tickers_multi',
                        'order_book',
                        'order_book/multi',
                        'fees/trading',
                        'trades',
                        'timestamp',
                    ),
                    'post' => array(
                        'k',
                    ),
                ),
                'private' => array(
                    'get' => array(
                        'key',
                        'members/me',
                        'orders',
                        'orders/filter',
                    ),
                    'post' => array(
                        'orders',
                        'orders/multi',
                        'order/delete',
                        'order/delete/multi',
                        'orders/clear',
                    ),
                ),
            ),
            'fees' => array(
                'trading' => array(
                    'tierBased' => false,
                    'percentage' => true,
                    'maker' => $this->parse_number('0.001'),
                    'taker' => $this->parse_number('0.001'),
                ),
            ),
            'commonCurrencies' => array(
                'PLA' => 'Plair',
            ),
            'exceptions' => array(
                'codes' => array(
                    '-1' => '\\ccxt\\BadRequest',
                    '-2' => '\\ccxt\\BadRequest',
                    '1001' => '\\ccxt\\BadRequest',
                    '1004' => '\\ccxt\\ArgumentsRequired',
                    '1006' => '\\ccxt\\AuthenticationError',
                    '1008' => '\\ccxt\\AuthenticationError',
                    '1010' => '\\ccxt\\AuthenticationError',
                    '1011' => '\\ccxt\\PermissionDenied',
                    '2001' => '\\ccxt\\AuthenticationError',
                    '2002' => '\\ccxt\\InvalidOrder',
                    '2004' => '\\ccxt\\OrderNotFound',
                    '9003' => '\\ccxt\\PermissionDenied',
                ),
                'exact' => array(
                    'market does not have a valid value' => '\\ccxt\\BadRequest',
                    'side does not have a valid value' => '\\ccxt\\BadRequest',
                    'Account::AccountError => Cannot lock funds' => '\\ccxt\\InsufficientFunds',
                    'The account does not exist' => '\\ccxt\\AuthenticationError',
                ),
            ),
        ));
    }

    public function fetch_markets($params = array ()) {
        /**
         * retrieves data on all $markets for oceanex
         * @param {dict} $params extra parameters specific to the exchange api endpoint
         * @return {[dict]} an array of objects representing $market data
         */
        $request = array( 'show_details' => true );
        $response = $this->publicGetMarkets (array_merge($request, $params));
        //
        //    array(
        //        $id => 'xtzusdt',
        //        $name => 'XTZ/USDT',
        //        ask_precision => '8',
        //        bid_precision => '8',
        //        enabled => true,
        //        price_precision => '4',
        //        amount_precision => '3',
        //        usd_precision => '4',
        //        minimum_trading_amount => '1.0'
        //    ),
        //
        $result = array();
        $markets = $this->safe_value($response, 'data', array());
        for ($i = 0; $i < count($markets); $i++) {
            $market = $markets[$i];
            $id = $this->safe_value($market, 'id');
            $name = $this->safe_value($market, 'name');
            list($baseId, $quoteId) = explode('/', $name);
            $base = $this->safe_currency_code($baseId);
            $quote = $this->safe_currency_code($quoteId);
            $baseId = strtolower($baseId);
            $quoteId = strtolower($quoteId);
            $symbol = $base . '/' . $quote;
            $result[] = array(
                'id' => $id,
                'symbol' => $symbol,
                'base' => $base,
                'quote' => $quote,
                'settle' => null,
                'baseId' => $baseId,
                'quoteId' => $quoteId,
                'settleId' => null,
                'type' => 'spot',
                'spot' => true,
                'margin' => false,
                'swap' => false,
                'future' => false,
                'option' => false,
                'active' => null,
                'contract' => false,
                'linear' => null,
                'inverse' => null,
                'contractSize' => null,
                'expiry' => null,
                'expiryDatetime' => null,
                'strike' => null,
                'optionType' => null,
                'precision' => array(
                    'amount' => $this->safe_integer($market, 'amount_precision'),
                    'price' => $this->safe_integer($market, 'price_precision'),
                    'base' => $this->safe_integer($market, 'ask_precision'),
                    'quote' => $this->safe_integer($market, 'bid_precision'),
                ),
                'limits' => array(
                    'leverage' => array(
                        'min' => null,
                        'max' => null,
                    ),
                    'amount' => array(
                        'min' => null,
                        'max' => null,
                    ),
                    'price' => array(
                        'min' => null,
                        'max' => null,
                    ),
                    'cost' => array(
                        'min' => $this->safe_number($market, 'minimum_trading_amount'),
                        'max' => null,
                    ),
                ),
                'info' => $market,
            );
        }
        return $result;
    }

    public function fetch_ticker($symbol, $params = array ()) {
        /**
         * fetches a price ticker, a statistical calculation with the information calculated over the past 24 hours for a specific $market
         * @param {str} $symbol unified $symbol of the $market to fetch the ticker for
         * @param {dict} $params extra parameters specific to the oceanex api endpoint
         * @return {dict} a {@link https://docs.ccxt.com/en/latest/manual.html#ticker-structure ticker structure}
         */
        $this->load_markets();
        $market = $this->market($symbol);
        $request = array(
            'pair' => $market['id'],
        );
        $response = $this->publicGetTickersPair (array_merge($request, $params));
        //
        //     {
        //         "code":0,
        //         "message":"Operation successful",
        //         "data" => {
        //             "at":1559431729,
        //             "ticker" => {
        //                 "buy":"0.0065",
        //                 "sell":"0.00677",
        //                 "low":"0.00677",
        //                 "high":"0.00677",
        //                 "last":"0.00677",
        //                 "vol":"2000.0"
        //             }
        //         }
        //     }
        //
        $data = $this->safe_value($response, 'data', array());
        return $this->parse_ticker($data, $market);
    }

    public function fetch_tickers($symbols = null, $params = array ()) {
        /**
         * fetches price tickers for multiple markets, statistical calculations with the information calculated over the past 24 hours each $market
         * @param {[str]|null} $symbols unified $symbols of the markets to fetch the $ticker for, all $market tickers are returned if not assigned
         * @param {dict} $params extra parameters specific to the oceanex api endpoint
         * @return {dict} an array of {@link https://docs.ccxt.com/en/latest/manual.html#$ticker-structure $ticker structures}
         */
        $this->load_markets();
        if ($symbols === null) {
            $symbols = $this->symbols;
        }
        $marketIds = $this->market_ids($symbols);
        $request = array( 'markets' => $marketIds );
        $response = $this->publicGetTickersMulti (array_merge($request, $params));
        //
        //     {
        //         "code":0,
        //         "message":"Operation successful",
        //         "data" => {
        //             "at":1559431729,
        //             "ticker" => {
        //                 "buy":"0.0065",
        //                 "sell":"0.00677",
        //                 "low":"0.00677",
        //                 "high":"0.00677",
        //                 "last":"0.00677",
        //                 "vol":"2000.0"
        //             }
        //         }
        //     }
        //
        $data = $this->safe_value($response, 'data', array());
        $result = array();
        for ($i = 0; $i < count($data); $i++) {
            $ticker = $data[$i];
            $marketId = $this->safe_string($ticker, 'market');
            $market = $this->safe_market($marketId);
            $symbol = $market['symbol'];
            $result[$symbol] = $this->parse_ticker($ticker, $market);
        }
        return $this->filter_by_array($result, 'symbol', $symbols);
    }

    public function parse_ticker($data, $market = null) {
        //
        //         {
        //             "at":1559431729,
        //             "ticker" => {
        //                 "buy":"0.0065",
        //                 "sell":"0.00677",
        //                 "low":"0.00677",
        //                 "high":"0.00677",
        //                 "last":"0.00677",
        //                 "vol":"2000.0"
        //             }
        //         }
        //
        $ticker = $this->safe_value($data, 'ticker', array());
        $timestamp = $this->safe_timestamp($data, 'at');
        $symbol = $this->safe_symbol(null, $market);
        return $this->safe_ticker(array(
            'symbol' => $symbol,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601($timestamp),
            'high' => $this->safe_string($ticker, 'high'),
            'low' => $this->safe_string($ticker, 'low'),
            'bid' => $this->safe_string($ticker, 'buy'),
            'bidVolume' => null,
            'ask' => $this->safe_string($ticker, 'sell'),
            'askVolume' => null,
            'vwap' => null,
            'open' => null,
            'close' => $this->safe_string($ticker, 'last'),
            'last' => $this->safe_string($ticker, 'last'),
            'previousClose' => null,
            'change' => null,
            'percentage' => null,
            'average' => null,
            'baseVolume' => $this->safe_string($ticker, 'volume'),
            'quoteVolume' => null,
            'info' => $ticker,
        ), $market);
    }

    public function fetch_order_book($symbol, $limit = null, $params = array ()) {
        /**
         * fetches information on open orders with bid (buy) and ask (sell) prices, volumes and other data
         * @param {str} $symbol unified $symbol of the $market to fetch the order book for
         * @param {int|null} $limit the maximum amount of order book entries to return
         * @param {dict} $params extra parameters specific to the oceanex api endpoint
         * @return {dict} A dictionary of {@link https://docs.ccxt.com/en/latest/manual.html#order-book-structure order book structures} indexed by $market symbols
         */
        $this->load_markets();
        $market = $this->market($symbol);
        $request = array(
            'market' => $market['id'],
        );
        if ($limit !== null) {
            $request['limit'] = $limit;
        }
        $response = $this->publicGetOrderBook (array_merge($request, $params));
        //
        //     {
        //         "code":0,
        //         "message":"Operation successful",
        //         "data" => {
        //             "timestamp":1559433057,
        //             "asks" => [
        //                 ["100.0","20.0"],
        //                 ["4.74","2000.0"],
        //                 ["1.74","4000.0"],
        //             ],
        //             "bids":[
        //                 ["0.0065","5482873.4"],
        //                 ["0.00649","4781956.2"],
        //                 ["0.00648","2876006.8"],
        //             ],
        //         }
        //     }
        //
        $orderbook = $this->safe_value($response, 'data', array());
        $timestamp = $this->safe_timestamp($orderbook, 'timestamp');
        return $this->parse_order_book($orderbook, $symbol, $timestamp);
    }

    public function fetch_order_books($symbols = null, $limit = null, $params = array ()) {
        $this->load_markets();
        if ($symbols === null) {
            $symbols = $this->symbols;
        }
        $marketIds = $this->market_ids($symbols);
        $request = array(
            'markets' => $marketIds,
        );
        if ($limit !== null) {
            $request['limit'] = $limit;
        }
        $response = $this->publicGetOrderBookMulti (array_merge($request, $params));
        //
        //     {
        //         "code":0,
        //         "message":"Operation successful",
        //         "data" => [
        //             array(
        //                 "timestamp":1559433057,
        //                 "market" => "bagvet",
        //                 "asks" => [
        //                     ["100.0","20.0"],
        //                     ["4.74","2000.0"],
        //                     ["1.74","4000.0"],
        //                 ],
        //                 "bids":[
        //                     ["0.0065","5482873.4"],
        //                     ["0.00649","4781956.2"],
        //                     ["0.00648","2876006.8"],
        //                 ],
        //             ),
        //             ...,
        //         ],
        //     }
        //
        $data = $this->safe_value($response, 'data', array());
        $result = array();
        for ($i = 0; $i < count($data); $i++) {
            $orderbook = $data[$i];
            $marketId = $this->safe_string($orderbook, 'market');
            $symbol = $this->safe_symbol($marketId);
            $timestamp = $this->safe_timestamp($orderbook, 'timestamp');
            $result[$symbol] = $this->parse_order_book($orderbook, $symbol, $timestamp);
        }
        return $result;
    }

    public function fetch_trades($symbol, $since = null, $limit = null, $params = array ()) {
        /**
         * get the list of most recent trades for a particular $symbol
         * @param {str} $symbol unified $symbol of the $market to fetch trades for
         * @param {int|null} $since timestamp in ms of the earliest trade to fetch
         * @param {int|null} $limit the maximum amount of trades to fetch
         * @param {dict} $params extra parameters specific to the oceanex api endpoint
         * @return {[dict]} a list of ~@link https://docs.ccxt.com/en/latest/manual.html?#public-trades trade structures~
         */
        $this->load_markets();
        $market = $this->market($symbol);
        $request = array(
            'market' => $market['id'],
        );
        if ($limit !== null) {
            $request['limit'] = $limit;
        }
        $response = $this->publicGetTrades (array_merge($request, $params));
        //
        //      {
        //          "code":0,
        //          "message":"Operation successful",
        //          "data" => array(
        //              array(
        //                  "id":220247666,
        //                  "price":"3098.62",
        //                  "volume":"0.00196",
        //                  "funds":"6.0732952",
        //                  "market":"ethusdt",
        //                  "created_at":"2022-04-19T19:03:15Z",
        //                  "created_on":1650394995,
        //                  "side":"bid"
        //              ),
        //          )
        //      }
        //
        $data = $this->safe_value($response, 'data');
        return $this->parse_trades($data, $market, $since, $limit);
    }

    public function parse_trade($trade, $market = null) {
        //
        // fetchTrades (public)
        //
        //      {
        //          "id":220247666,
        //          "price":"3098.62",
        //          "volume":"0.00196",
        //          "funds":"6.0732952",
        //          "market":"ethusdt",
        //          "created_at":"2022-04-19T19:03:15Z",
        //          "created_on":1650394995,
        //          "side":"bid"
        //      }
        //
        $side = $this->safe_value($trade, 'side');
        if ($side === 'bid') {
            $side = 'buy';
        } elseif ($side === 'ask') {
            $side = 'sell';
        }
        $marketId = $this->safe_value($trade, 'market');
        $symbol = $this->safe_symbol($marketId, $market);
        $timestamp = $this->safe_timestamp($trade, 'created_on');
        if ($timestamp === null) {
            $timestamp = $this->parse8601($this->safe_string($trade, 'created_at'));
        }
        $priceString = $this->safe_string($trade, 'price');
        $amountString = $this->safe_string($trade, 'volume');
        return $this->safe_trade(array(
            'info' => $trade,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601($timestamp),
            'symbol' => $symbol,
            'id' => $this->safe_string($trade, 'id'),
            'order' => null,
            'type' => 'limit',
            'takerOrMaker' => null,
            'side' => $side,
            'price' => $priceString,
            'amount' => $amountString,
            'cost' => null,
            'fee' => null,
        ), $market);
    }

    public function fetch_time($params = array ()) {
        /**
         * fetches the current integer timestamp in milliseconds from the exchange server
         * @param {dict} $params extra parameters specific to the oceanex api endpoint
         * @return {int} the current integer timestamp in milliseconds from the exchange server
         */
        $response = $this->publicGetTimestamp ($params);
        //
        //     array("code":0,"message":"Operation successful","data":1559433420)
        //
        return $this->safe_timestamp($response, 'data');
    }

    public function fetch_trading_fees($params = array ()) {
        $response = $this->publicGetFeesTrading ($params);
        $data = $this->safe_value($response, 'data', array());
        $result = array();
        for ($i = 0; $i < count($data); $i++) {
            $group = $data[$i];
            $maker = $this->safe_value($group, 'ask_fee', array());
            $taker = $this->safe_value($group, 'bid_fee', array());
            $marketId = $this->safe_string($group, 'market');
            $symbol = $this->safe_symbol($marketId);
            $result[$symbol] = array(
                'info' => $group,
                'symbol' => $symbol,
                'maker' => $this->safe_number($maker, 'value'),
                'taker' => $this->safe_number($taker, 'value'),
                'percentage' => true,
            );
        }
        return $result;
    }

    public function fetch_key($params = array ()) {
        $response = $this->privateGetKey ($params);
        return $this->safe_value($response, 'data');
    }

    public function parse_balance($response) {
        $data = $this->safe_value($response, 'data');
        $balances = $this->safe_value($data, 'accounts', array());
        $result = array( 'info' => $response );
        for ($i = 0; $i < count($balances); $i++) {
            $balance = $balances[$i];
            $currencyId = $this->safe_value($balance, 'currency');
            $code = $this->safe_currency_code($currencyId);
            $account = $this->account();
            $account['free'] = $this->safe_string($balance, 'balance');
            $account['used'] = $this->safe_string($balance, 'locked');
            $result[$code] = $account;
        }
        return $this->safe_balance($result);
    }

    public function fetch_balance($params = array ()) {
        /**
         * query for balance and get the amount of funds available for trading or funds locked in orders
         * @param {dict} $params extra parameters specific to the oceanex api endpoint
         * @return {dict} a ~@link https://docs.ccxt.com/en/latest/manual.html?#balance-structure balance structure~
         */
        $this->load_markets();
        $response = $this->privateGetMembersMe ($params);
        return $this->parse_balance($response);
    }

    public function create_order($symbol, $type, $side, $amount, $price = null, $params = array ()) {
        /**
         * create a trade order
         * @param {str} $symbol unified $symbol of the $market to create an order in
         * @param {str} $type 'market' or 'limit'
         * @param {str} $side 'buy' or 'sell'
         * @param {float} $amount how much of currency you want to trade in units of base currency
         * @param {float} $price the $price at which the order is to be fullfilled, in units of the quote currency, ignored in $market orders
         * @param {dict} $params extra parameters specific to the oceanex api endpoint
         * @return {dict} an {@link https://docs.ccxt.com/en/latest/manual.html#order-structure order structure}
         */
        $this->load_markets();
        $market = $this->market($symbol);
        $request = array(
            'market' => $market['id'],
            'side' => $side,
            'ord_type' => $type,
            'volume' => $this->amount_to_precision($symbol, $amount),
        );
        if ($type === 'limit') {
            $request['price'] = $this->price_to_precision($symbol, $price);
        }
        $response = $this->privatePostOrders (array_merge($request, $params));
        $data = $this->safe_value($response, 'data');
        return $this->parse_order($data, $market);
    }

    public function fetch_order($id, $symbol = null, $params = array ()) {
        /**
         * fetches information on an order made by the user
         * @param {str|null} $symbol unified $symbol of the $market the order was made in
         * @param {dict} $params extra parameters specific to the oceanex api endpoint
         * @return {dict} An {@link https://docs.ccxt.com/en/latest/manual.html#order-structure order structure}
         */
        $ids = $id;
        if (gettype($id) === 'array' && count(array_filter(array_keys($id), 'is_string')) != 0) {
            $ids = array( $id );
        }
        $this->load_markets();
        $market = null;
        if ($symbol !== null) {
            $market = $this->market($symbol);
        }
        $request = array( 'ids' => $ids );
        $response = $this->privateGetOrders (array_merge($request, $params));
        $data = $this->safe_value($response, 'data');
        $dataLength = is_array($data) ? count($data) : 0;
        if ($data === null) {
            throw new OrderNotFound($this->id . ' could not found matching order');
        }
        if (gettype($id) === 'array' && count(array_filter(array_keys($id), 'is_string')) == 0) {
            return $this->parse_orders($data, $market);
        }
        if ($dataLength === 0) {
            throw new OrderNotFound($this->id . ' could not found matching order');
        }
        return $this->parse_order($data[0], $market);
    }

    public function fetch_open_orders($symbol = null, $since = null, $limit = null, $params = array ()) {
        $request = array(
            'states' => array( 'wait' ),
        );
        return $this->fetch_orders($symbol, $since, $limit, array_merge($request, $params));
    }

    public function fetch_closed_orders($symbol = null, $since = null, $limit = null, $params = array ()) {
        $request = array(
            'states' => array( 'done', 'cancel' ),
        );
        return $this->fetch_orders($symbol, $since, $limit, array_merge($request, $params));
    }

    public function fetch_orders($symbol = null, $since = null, $limit = null, $params = array ()) {
        if ($symbol === null) {
            throw new ArgumentsRequired($this->id . ' fetchOrders() requires a `$symbol` argument');
        }
        $this->load_markets();
        $market = $this->market($symbol);
        $states = $this->safe_value($params, 'states', array( 'wait', 'done', 'cancel' ));
        $query = $this->omit($params, 'states');
        $request = array(
            'market' => $market['id'],
            'states' => $states,
            'need_price' => 'True',
        );
        if ($limit !== null) {
            $request['limit'] = $limit;
        }
        $response = $this->privateGetOrdersFilter (array_merge($request, $query));
        $data = $this->safe_value($response, 'data', array());
        $result = array();
        for ($i = 0; $i < count($data); $i++) {
            $orders = $this->safe_value($data[$i], 'orders', array());
            $status = $this->parse_order_status($this->safe_value($data[$i], 'state'));
            $parsedOrders = $this->parse_orders($orders, $market, $since, $limit, array( 'status' => $status ));
            $result = $this->array_concat($result, $parsedOrders);
        }
        return $result;
    }

    public function parse_ohlcv($ohlcv, $market = null) {
        // array(
        //    1559232000,
        //    8889.22,
        //    9028.52,
        //    8889.22,
        //    9028.52
        //    0.3121
        // )
        return array(
            $this->safe_timestamp($ohlcv, 0),
            $this->safe_number($ohlcv, 1),
            $this->safe_number($ohlcv, 2),
            $this->safe_number($ohlcv, 3),
            $this->safe_number($ohlcv, 4),
            $this->safe_number($ohlcv, 5),
        );
    }

    public function fetch_ohlcv($symbol, $timeframe = '1m', $since = null, $limit = null, $params = array ()) {
        /**
         * fetches historical candlestick data containing the open, high, low, and close price, and the volume of a $market
         * @param {str} $symbol unified $symbol of the $market to fetch OHLCV data for
         * @param {str} $timeframe the length of time each candle represents
         * @param {int|null} $since timestamp in ms of the earliest candle to fetch
         * @param {int|null} $limit the maximum amount of candles to fetch
         * @param {dict} $params extra parameters specific to the oceanex api endpoint
         * @return {[[int]]} A list of candles ordered as timestamp, open, high, low, close, volume
         */
        $this->load_markets();
        $market = $this->market($symbol);
        $request = array(
            'market' => $market['id'],
            'period' => $this->timeframes[$timeframe],
        );
        if ($since !== null) {
            $request['timestamp'] = $since;
        }
        if ($limit !== null) {
            $request['limit'] = $limit;
        }
        $response = $this->publicPostK (array_merge($request, $params));
        $ohlcvs = $this->safe_value($response, 'data', array());
        return $this->parse_ohlcvs($ohlcvs, $market, $timeframe, $since, $limit);
    }

    public function parse_order($order, $market = null) {
        //
        //     {
        //         "created_at" => "2019-01-18T00:38:18Z",
        //         "trades_count" => 0,
        //         "remaining_volume" => "0.2",
        //         "price" => "1001.0",
        //         "created_on" => "1547771898",
        //         "side" => "buy",
        //         "volume" => "0.2",
        //         "state" => "wait",
        //         "ord_type" => "limit",
        //         "avg_price" => "0.0",
        //         "executed_volume" => "0.0",
        //         "id" => 473797,
        //         "market" => "veteth"
        //     }
        //
        $status = $this->parse_order_status($this->safe_value($order, 'state'));
        $marketId = $this->safe_string_2($order, 'market', 'market_id');
        $symbol = $this->safe_symbol($marketId, $market);
        $timestamp = $this->safe_timestamp($order, 'created_on');
        if ($timestamp === null) {
            $timestamp = $this->parse8601($this->safe_string($order, 'created_at'));
        }
        $price = $this->safe_string($order, 'price');
        $average = $this->safe_string($order, 'avg_price');
        $amount = $this->safe_string($order, 'volume');
        $remaining = $this->safe_string($order, 'remaining_volume');
        $filled = $this->safe_string($order, 'executed_volume');
        return $this->safe_order(array(
            'info' => $order,
            'id' => $this->safe_string($order, 'id'),
            'clientOrderId' => null,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601($timestamp),
            'lastTradeTimestamp' => null,
            'symbol' => $symbol,
            'type' => $this->safe_value($order, 'ord_type'),
            'timeInForce' => null,
            'postOnly' => null,
            'side' => $this->safe_value($order, 'side'),
            'price' => $price,
            'stopPrice' => null,
            'average' => $average,
            'amount' => $amount,
            'remaining' => $remaining,
            'filled' => $filled,
            'status' => $status,
            'cost' => null,
            'trades' => null,
            'fee' => null,
        ), $market);
    }

    public function parse_order_status($status) {
        $statuses = array(
            'wait' => 'open',
            'done' => 'closed',
            'cancel' => 'canceled',
        );
        return $this->safe_string($statuses, $status, $status);
    }

    public function create_orders($symbol, $orders, $params = array ()) {
        $this->load_markets();
        $market = $this->market($symbol);
        $request = array(
            'market' => $market['id'],
            'orders' => $orders,
        );
        // $orders => [array("side":"buy", "volume":.2, "price":1001), array("side":"sell", "volume":0.2, "price":1002)]
        $response = $this->privatePostOrdersMulti (array_merge($request, $params));
        $data = $response['data'];
        return $this->parse_orders($data);
    }

    public function cancel_order($id, $symbol = null, $params = array ()) {
        /**
         * cancels an open order
         * @param {str} $id order $id
         * @param {str|null} $symbol not used by oceanex cancelOrder ()
         * @param {dict} $params extra parameters specific to the oceanex api endpoint
         * @return {dict} An {@link https://docs.ccxt.com/en/latest/manual.html#order-structure order structure}
         */
        $this->load_markets();
        $response = $this->privatePostOrderDelete (array_merge(array( 'id' => $id ), $params));
        $data = $this->safe_value($response, 'data');
        return $this->parse_order($data);
    }

    public function cancel_orders($ids, $symbol = null, $params = array ()) {
        $this->load_markets();
        $response = $this->privatePostOrderDeleteMulti (array_merge(array( 'ids' => $ids ), $params));
        $data = $this->safe_value($response, 'data');
        return $this->parse_orders($data);
    }

    public function cancel_all_orders($symbol = null, $params = array ()) {
        $this->load_markets();
        $response = $this->privatePostOrdersClear ($params);
        $data = $this->safe_value($response, 'data');
        return $this->parse_orders($data);
    }

    public function sign($path, $api = 'public', $method = 'GET', $params = array (), $headers = null, $body = null) {
        $url = $this->urls['api'] . '/' . $this->version . '/' . $this->implode_params($path, $params);
        $query = $this->omit($params, $this->extract_params($path));
        if ($api === 'public') {
            if ($path === 'tickers_multi' || $path === 'order_book/multi') {
                $request = '?';
                $markets = $this->safe_value($params, 'markets');
                for ($i = 0; $i < count($markets); $i++) {
                    $request .= 'marketsarray()=' . $markets[$i] . '&';
                }
                $limit = $this->safe_value($params, 'limit');
                if ($limit !== null) {
                    $request .= 'limit=' . $limit;
                }
                $url .= $request;
            } elseif ($query) {
                $url .= '?' . $this->urlencode($query);
            }
        } elseif ($api === 'private') {
            $this->check_required_credentials();
            $request = array(
                'uid' => $this->apiKey,
                'data' => $query,
            );
            // to set the private key:
            // $fs = require ('fs')
            // exchange.secret = $fs->readFileSync ('oceanex.pem', 'utf8')
            $jwt_token = $this->jwt($request, $this->encode($this->secret), 'RS256');
            $url .= '?user_jwt=' . $jwt_token;
        }
        $headers = array( 'Content-Type' => 'application/json' );
        return array( 'url' => $url, 'method' => $method, 'body' => $body, 'headers' => $headers );
    }

    public function handle_errors($code, $reason, $url, $method, $headers, $body, $response, $requestHeaders, $requestBody) {
        //
        //     array("code":1011,"message":"This IP 'x.x.x.x' is not allowed","data":array())
        //
        if ($response === null) {
            return;
        }
        $errorCode = $this->safe_string($response, 'code');
        $message = $this->safe_string($response, 'message');
        if (($errorCode !== null) && ($errorCode !== '0')) {
            $feedback = $this->id . ' ' . $body;
            $this->throw_exactly_matched_exception($this->exceptions['codes'], $errorCode, $feedback);
            $this->throw_exactly_matched_exception($this->exceptions['exact'], $message, $feedback);
            throw new ExchangeError($feedback);
        }
    }
}
