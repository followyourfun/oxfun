# -*- coding: utf-8 -*-

# PLEASE DO NOT EDIT THIS FILE, IT IS GENERATED AND WILL BE OVERWRITTEN:
# https://github.com/ccxt/ccxt/blob/master/CONTRIBUTING.md#how-to-contribute-code

from ccxtpro.base.exchange import Exchange
import ccxt.async_support as ccxt
from ccxtpro.base.cache import ArrayCache
from ccxt.base.errors import ExchangeError
from ccxt.base.errors import NotSupported
from ccxt.base.errors import RateLimitExceeded


class bitmex(Exchange, ccxt.bitmex):

    def describe(self):
        return self.deep_extend(super(bitmex, self).describe(), {
            'has': {
                'ws': True,
                'watchTicker': True,
                'watchTickers': False,
                'watchTrades': True,
                'watchOrderBook': True,
                'watchOHLCV': True,
            },
            'urls': {
                'api': {
                    'ws': 'wss://www.bitmex.com/realtime',
                },
            },
            'versions': {
                'ws': '0.2.0',
            },
            'options': {
                'watchOrderBookLevel': 'orderBookL2',  # 'orderBookL2' = L2 full order book, 'orderBookL2_25' = L2 top 25, 'orderBook10' L3 top 10
                'tradesLimit': 1000,
                'OHLCVLimit': 1000,
            },
            'exceptions': {
                'ws': {
                    'exact': {
                    },
                    'broad': {
                        'Rate limit exceeded': RateLimitExceeded,
                    },
                },
            },
        })

    async def watch_ticker(self, symbol, params={}):
        await self.load_markets()
        market = self.market(symbol)
        name = 'instrument'
        messageHash = name + ':' + market['id']
        url = self.urls['api']['ws']
        request = {
            'op': 'subscribe',
            'args': [
                messageHash,
            ],
        }
        return await self.watch(url, messageHash, self.extend(request, params), messageHash)

    def handle_ticker(self, client, message):
        #
        #     {
        #         table: 'instrument',
        #         action: 'partial',
        #         keys: ['symbol'],
        #         types: {
        #             symbol: 'symbol',
        #             rootSymbol: 'symbol',
        #             state: 'symbol',
        #             typ: 'symbol',
        #             listing: 'timestamp',
        #             front: 'timestamp',
        #             expiry: 'timestamp',
        #             settle: 'timestamp',
        #             relistInterval: 'timespan',
        #             inverseLeg: 'symbol',
        #             sellLeg: 'symbol',
        #             buyLeg: 'symbol',
        #             optionStrikePcnt: 'float',
        #             optionStrikeRound: 'float',
        #             optionStrikePrice: 'float',
        #             optionMultiplier: 'float',
        #             positionCurrency: 'symbol',
        #             underlying: 'symbol',
        #             quoteCurrency: 'symbol',
        #             underlyingSymbol: 'symbol',
        #             reference: 'symbol',
        #             referenceSymbol: 'symbol',
        #             calcInterval: 'timespan',
        #             publishInterval: 'timespan',
        #             publishTime: 'timespan',
        #             maxOrderQty: 'long',
        #             maxPrice: 'float',
        #             lotSize: 'long',
        #             tickSize: 'float',
        #             multiplier: 'long',
        #             settlCurrency: 'symbol',
        #             underlyingToPositionMultiplier: 'long',
        #             underlyingToSettleMultiplier: 'long',
        #             quoteToSettleMultiplier: 'long',
        #             isQuanto: 'boolean',
        #             isInverse: 'boolean',
        #             initMargin: 'float',
        #             maintMargin: 'float',
        #             riskLimit: 'long',
        #             riskStep: 'long',
        #             limit: 'float',
        #             capped: 'boolean',
        #             taxed: 'boolean',
        #             deleverage: 'boolean',
        #             makerFee: 'float',
        #             takerFee: 'float',
        #             settlementFee: 'float',
        #             insuranceFee: 'float',
        #             fundingBaseSymbol: 'symbol',
        #             fundingQuoteSymbol: 'symbol',
        #             fundingPremiumSymbol: 'symbol',
        #             fundingTimestamp: 'timestamp',
        #             fundingInterval: 'timespan',
        #             fundingRate: 'float',
        #             indicativeFundingRate: 'float',
        #             rebalanceTimestamp: 'timestamp',
        #             rebalanceInterval: 'timespan',
        #             openingTimestamp: 'timestamp',
        #             closingTimestamp: 'timestamp',
        #             sessionInterval: 'timespan',
        #             prevClosePrice: 'float',
        #             limitDownPrice: 'float',
        #             limitUpPrice: 'float',
        #             bankruptLimitDownPrice: 'float',
        #             bankruptLimitUpPrice: 'float',
        #             prevTotalVolume: 'long',
        #             totalVolume: 'long',
        #             volume: 'long',
        #             volume24h: 'long',
        #             prevTotalTurnover: 'long',
        #             totalTurnover: 'long',
        #             turnover: 'long',
        #             turnover24h: 'long',
        #             homeNotional24h: 'float',
        #             foreignNotional24h: 'float',
        #             prevPrice24h: 'float',
        #             vwap: 'float',
        #             highPrice: 'float',
        #             lowPrice: 'float',
        #             lastPrice: 'float',
        #             lastPriceProtected: 'float',
        #             lastTickDirection: 'symbol',
        #             lastChangePcnt: 'float',
        #             bidPrice: 'float',
        #             midPrice: 'float',
        #             askPrice: 'float',
        #             impactBidPrice: 'float',
        #             impactMidPrice: 'float',
        #             impactAskPrice: 'float',
        #             hasLiquidity: 'boolean',
        #             openInterest: 'long',
        #             openValue: 'long',
        #             fairMethod: 'symbol',
        #             fairBasisRate: 'float',
        #             fairBasis: 'float',
        #             fairPrice: 'float',
        #             markMethod: 'symbol',
        #             markPrice: 'float',
        #             indicativeTaxRate: 'float',
        #             indicativeSettlePrice: 'float',
        #             optionUnderlyingPrice: 'float',
        #             settledPrice: 'float',
        #             timestamp: 'timestamp'
        #         },
        #         foreignKeys: {
        #             inverseLeg: 'instrument',
        #             sellLeg: 'instrument',
        #             buyLeg: 'instrument'
        #         },
        #         attributes: {symbol: 'unique'},
        #         filter: {symbol: 'XBTUSD'},
        #         data: [
        #             {
        #                 symbol: 'XBTUSD',
        #                 rootSymbol: 'XBT',
        #                 state: 'Open',
        #                 typ: 'FFWCSX',
        #                 listing: '2016-05-13T12:00:00.000Z',
        #                 front: '2016-05-13T12:00:00.000Z',
        #                 expiry: null,
        #                 settle: null,
        #                 relistInterval: null,
        #                 inverseLeg: '',
        #                 sellLeg: '',
        #                 buyLeg: '',
        #                 optionStrikePcnt: null,
        #                 optionStrikeRound: null,
        #                 optionStrikePrice: null,
        #                 optionMultiplier: null,
        #                 positionCurrency: 'USD',
        #                 underlying: 'XBT',
        #                 quoteCurrency: 'USD',
        #                 underlyingSymbol: 'XBT=',
        #                 reference: 'BMEX',
        #                 referenceSymbol: '.BXBT',
        #                 calcInterval: null,
        #                 publishInterval: null,
        #                 publishTime: null,
        #                 maxOrderQty: 10000000,
        #                 maxPrice: 1000000,
        #                 lotSize: 1,
        #                 tickSize: 0.5,
        #                 multiplier: -100000000,
        #                 settlCurrency: 'XBt',
        #                 underlyingToPositionMultiplier: null,
        #                 underlyingToSettleMultiplier: -100000000,
        #                 quoteToSettleMultiplier: null,
        #                 isQuanto: False,
        #                 isInverse: True,
        #                 initMargin: 0.01,
        #                 maintMargin: 0.005,
        #                 riskLimit: 20000000000,
        #                 riskStep: 10000000000,
        #                 limit: null,
        #                 capped: False,
        #                 taxed: True,
        #                 deleverage: True,
        #                 makerFee: -0.00025,
        #                 takerFee: 0.00075,
        #                 settlementFee: 0,
        #                 insuranceFee: 0,
        #                 fundingBaseSymbol: '.XBTBON8H',
        #                 fundingQuoteSymbol: '.USDBON8H',
        #                 fundingPremiumSymbol: '.XBTUSDPI8H',
        #                 fundingTimestamp: '2020-01-29T12:00:00.000Z',
        #                 fundingInterval: '2000-01-01T08:00:00.000Z',
        #                 fundingRate: 0.000597,
        #                 indicativeFundingRate: 0.000652,
        #                 rebalanceTimestamp: null,
        #                 rebalanceInterval: null,
        #                 openingTimestamp: '2020-01-29T11:00:00.000Z',
        #                 closingTimestamp: '2020-01-29T12:00:00.000Z',
        #                 sessionInterval: '2000-01-01T01:00:00.000Z',
        #                 prevClosePrice: 9063.96,
        #                 limitDownPrice: null,
        #                 limitUpPrice: null,
        #                 bankruptLimitDownPrice: null,
        #                 bankruptLimitUpPrice: null,
        #                 prevTotalVolume: 1989881049026,
        #                 totalVolume: 1990196740950,
        #                 volume: 315691924,
        #                 volume24h: 4491824765,
        #                 prevTotalTurnover: 27865497128425564,
        #                 totalTurnover: 27868891594857150,
        #                 turnover: 3394466431587,
        #                 turnover24h: 48863390064843,
        #                 homeNotional24h: 488633.9006484273,
        #                 foreignNotional24h: 4491824765,
        #                 prevPrice24h: 9091,
        #                 vwap: 9192.8663,
        #                 highPrice: 9440,
        #                 lowPrice: 8886,
        #                 lastPrice: 9287,
        #                 lastPriceProtected: 9287,
        #                 lastTickDirection: 'PlusTick',
        #                 lastChangePcnt: 0.0216,
        #                 bidPrice: 9286,
        #                 midPrice: 9286.25,
        #                 askPrice: 9286.5,
        #                 impactBidPrice: 9285.9133,
        #                 impactMidPrice: 9286.75,
        #                 impactAskPrice: 9287.6382,
        #                 hasLiquidity: True,
        #                 openInterest: 967826984,
        #                 openValue: 10432207060536,
        #                 fairMethod: 'FundingRate',
        #                 fairBasisRate: 0.6537149999999999,
        #                 fairBasis: 0.33,
        #                 fairPrice: 9277.2,
        #                 markMethod: 'FairPrice',
        #                 markPrice: 9277.2,
        #                 indicativeTaxRate: 0,
        #                 indicativeSettlePrice: 9276.87,
        #                 optionUnderlyingPrice: null,
        #                 settledPrice: null,
        #                 timestamp: '2020-01-29T11:31:37.114Z'
        #             }
        #         ]
        #     }
        #
        table = self.safe_string(message, 'table')
        data = self.safe_value(message, 'data', [])
        for i in range(0, len(data)):
            update = data[i]
            marketId = self.safe_value(update, 'symbol')
            if marketId in self.markets_by_id:
                market = self.markets_by_id[marketId]
                symbol = market['symbol']
                messageHash = table + ':' + marketId
                ticker = self.safe_value(self.tickers, symbol, {})
                info = self.safe_value(ticker, 'info', {})
                ticker = self.parse_ticker(self.extend(info, update), market)
                self.tickers[symbol] = ticker
                client.resolve(ticker, messageHash)
        return message

    async def watch_balance(self, params={}):
        await self.load_markets()
        raise NotSupported(self.id + ' watchBalance() not implemented yet')

    def handle_trades(self, client, message):
        #
        # initial snapshot
        #
        #     {
        #         table: 'trade',
        #         action: 'partial',
        #         keys: [],
        #         types: {
        #             timestamp: 'timestamp',
        #             symbol: 'symbol',
        #             side: 'symbol',
        #             size: 'long',
        #             price: 'float',
        #             tickDirection: 'symbol',
        #             trdMatchID: 'guid',
        #             grossValue: 'long',
        #             homeNotional: 'float',
        #             foreignNotional: 'float'
        #         },
        #         foreignKeys: {symbol: 'instrument', side: 'side'},
        #         attributes: {timestamp: 'sorted', symbol: 'grouped'},
        #         filter: {symbol: 'XBTUSD'},
        #         data: [
        #             {
        #                 timestamp: '2020-01-30T17:03:07.854Z',
        #                 symbol: 'XBTUSD',
        #                 side: 'Buy',
        #                 size: 15000,
        #                 price: 9378,
        #                 tickDirection: 'ZeroPlusTick',
        #                 trdMatchID: '5b426e7f-83d1-2c80-295d-ee995b8ceb4a',
        #                 grossValue: 159945000,
        #                 homeNotional: 1.59945,
        #                 foreignNotional: 15000
        #             }
        #         ]
        #     }
        #
        # updates
        #
        #     {
        #         table: 'trade',
        #         action: 'insert',
        #         data: [
        #             {
        #                 timestamp: '2020-01-30T17:31:40.160Z',
        #                 symbol: 'XBTUSD',
        #                 side: 'Sell',
        #                 size: 37412,
        #                 price: 9521.5,
        #                 tickDirection: 'ZeroMinusTick',
        #                 trdMatchID: 'a4bfc6bc-6cf1-1a11-622e-270eef8ca5c7',
        #                 grossValue: 392938236,
        #                 homeNotional: 3.92938236,
        #                 foreignNotional: 37412
        #             }
        #         ]
        #     }
        #
        table = 'trade'
        data = self.safe_value(message, 'data', [])
        dataByMarketIds = self.group_by(data, 'symbol')
        marketIds = list(dataByMarketIds.keys())
        for i in range(0, len(marketIds)):
            marketId = marketIds[i]
            if marketId in self.markets_by_id:
                market = self.markets_by_id[marketId]
                messageHash = table + ':' + marketId
                symbol = market['symbol']
                trades = self.parse_trades(dataByMarketIds[marketId], market)
                stored = self.safe_value(self.trades, symbol)
                if stored is None:
                    limit = self.safe_integer(self.options, 'tradesLimit', 1000)
                    stored = ArrayCache(limit)
                    self.trades[symbol] = stored
                for j in range(0, len(trades)):
                    stored.append(trades[j])
                client.resolve(stored, messageHash)

    async def watch_trades(self, symbol, since=None, limit=None, params={}):
        await self.load_markets()
        market = self.market(symbol)
        table = 'trade'
        messageHash = table + ':' + market['id']
        url = self.urls['api']['ws']
        request = {
            'op': 'subscribe',
            'args': [
                messageHash,
            ],
        }
        future = self.watch(url, messageHash, self.extend(request, params), messageHash)
        return await self.after(future, self.filter_by_since_limit, since, limit, 'timestamp', True)

    async def watch_order_book(self, symbol, limit=None, params={}):
        table = None
        if limit is None:
            table = self.safe_string(self.options, 'watchOrderBookLevel', 'orderBookL2')
        elif limit == 25:
            table = 'orderBookL2_25'
        elif limit == 10:
            table = 'orderBookL10'
        else:
            raise ExchangeError(self.id + ' watchOrderBook limit argument must be None(L2), 25(L2) or 10(L3)')
        await self.load_markets()
        market = self.market(symbol)
        messageHash = table + ':' + market['id']
        url = self.urls['api']['ws']
        request = {
            'op': 'subscribe',
            'args': [
                messageHash,
            ],
        }
        future = self.watch(url, messageHash, self.deep_extend(request, params), messageHash)
        return await self.after(future, self.limit_order_book, symbol, limit, params)

    async def watch_ohlcv(self, symbol, timeframe='1m', since=None, limit=None, params={}):
        await self.load_markets()
        market = self.market(symbol)
        table = 'tradeBin' + self.timeframes[timeframe]
        messageHash = table + ':' + market['id']
        url = self.urls['api']['ws']
        request = {
            'op': 'subscribe',
            'args': [
                messageHash,
            ],
        }
        future = self.watch(url, messageHash, self.extend(request, params), messageHash)
        return await self.after(future, self.filter_by_since_limit, since, limit, 0, True)

    def find_timeframe(self, timeframe):
        keys = list(self.timeframes.keys())
        for i in range(0, len(keys)):
            key = keys[i]
            if self.timeframes[key] == timeframe:
                return key
        return None

    def handle_ohlcv(self, client, message):
        #
        #     {
        #         table: 'tradeBin1m',
        #         action: 'partial',
        #         keys: [],
        #         types: {
        #             timestamp: 'timestamp',
        #             symbol: 'symbol',
        #             open: 'float',
        #             high: 'float',
        #             low: 'float',
        #             close: 'float',
        #             trades: 'long',
        #             volume: 'long',
        #             vwap: 'float',
        #             lastSize: 'long',
        #             turnover: 'long',
        #             homeNotional: 'float',
        #             foreignNotional: 'float'
        #         },
        #         foreignKeys: {symbol: 'instrument'},
        #         attributes: {timestamp: 'sorted', symbol: 'grouped'},
        #         filter: {symbol: 'XBTUSD'},
        #         data: [
        #             {
        #                 timestamp: '2020-02-03T01:13:00.000Z',
        #                 symbol: 'XBTUSD',
        #                 open: 9395,
        #                 high: 9395.5,
        #                 low: 9394.5,
        #                 close: 9395,
        #                 trades: 221,
        #                 volume: 839204,
        #                 vwap: 9394.9643,
        #                 lastSize: 1874,
        #                 turnover: 8932641535,
        #                 homeNotional: 89.32641534999999,
        #                 foreignNotional: 839204
        #             }
        #         ]
        #     }
        #
        #
        #     {
        #         table: 'tradeBin1m',
        #         action: 'insert',
        #         data: [
        #             {
        #                 timestamp: '2020-02-03T18:28:00.000Z',
        #                 symbol: 'XBTUSD',
        #                 open: 9256,
        #                 high: 9256.5,
        #                 low: 9256,
        #                 close: 9256,
        #                 trades: 29,
        #                 volume: 79057,
        #                 vwap: 9256.688,
        #                 lastSize: 100,
        #                 turnover: 854077082,
        #                 homeNotional: 8.540770820000002,
        #                 foreignNotional: 79057
        #             }
        #         ]
        #     }
        #
        # --------------------------------------------------------------------
        table = self.safe_string(message, 'table')
        interval = table.replace('tradeBin', '')
        timeframe = self.find_timeframe(interval)
        duration = self.parse_timeframe(timeframe)
        candles = self.safe_value(message, 'data', [])
        results = {}
        for i in range(0, len(candles)):
            candle = candles[i]
            marketId = self.safe_string(candle, 'symbol')
            if marketId in self.markets_by_id:
                market = self.markets_by_id[marketId]
                symbol = market['symbol']
                messageHash = table + ':' + market['id']
                result = [
                    self.parse8601(self.safe_string(candle, 'timestamp')) - duration * 1000,
                    self.safe_float(candle, 'open'),
                    self.safe_float(candle, 'high'),
                    self.safe_float(candle, 'low'),
                    self.safe_float(candle, 'close'),
                    self.safe_float(candle, 'volume'),
                ]
                self.ohlcvs[symbol] = self.safe_value(self.ohlcvs, symbol, {})
                stored = self.safe_value(self.ohlcvs[symbol], timeframe)
                if stored is None:
                    limit = self.safe_integer(self.options, 'OHLCVLimit', 1000)
                    stored = ArrayCache(limit)
                    self.ohlcvs[symbol][timeframe] = stored
                length = len(stored)
                if length and result[0] == stored[length - 1][0]:
                    stored[length - 1] = result
                else:
                    stored.append(result)
                results[messageHash] = stored
        messageHashes = list(results.keys())
        for i in range(0, len(messageHashes)):
            messageHash = messageHashes[i]
            client.resolve(results[messageHash], messageHash)

    async def watch_heartbeat(self, params={}):
        await self.load_markets()
        event = 'heartbeat'
        url = self.urls['api']['ws']
        return await self.watch(url, event)

    def sign_message(self, client, messageHash, message, params={}):
        # todo bitmex signMessage not implemented yet
        return message

    def handle_order_book(self, client, message):
        #
        # first snapshot
        #
        #     {
        #         table: 'orderBookL2',
        #         action: 'partial',
        #         keys: ['symbol', 'id', 'side'],
        #         types: {
        #             symbol: 'symbol',
        #             id: 'long',
        #             side: 'symbol',
        #             size: 'long',
        #             price: 'float'
        #         },
        #         foreignKeys: {symbol: 'instrument', side: 'side'},
        #         attributes: {symbol: 'parted', id: 'sorted'},
        #         filter: {symbol: 'XBTUSD'},
        #         data: [
        #             {symbol: 'XBTUSD', id: 8700000100, side: 'Sell', size: 1, price: 999999},
        #             {symbol: 'XBTUSD', id: 8700000200, side: 'Sell', size: 3, price: 999998},
        #             {symbol: 'XBTUSD', id: 8716991250, side: 'Sell', size: 26, price: 830087.5},
        #             {symbol: 'XBTUSD', id: 8728701950, side: 'Sell', size: 1720, price: 712980.5},
        #         ]
        #     }
        #
        # subsequent updates
        #
        #     {
        #         table: 'orderBookL2',
        #         action: 'update',
        #         data: [
        #             {symbol: 'XBTUSD', id: 8799285100, side: 'Sell', size: 70590},
        #             {symbol: 'XBTUSD', id: 8799285550, side: 'Sell', size: 217652},
        #             {symbol: 'XBTUSD', id: 8799288950, side: 'Buy', size: 47552},
        #             {symbol: 'XBTUSD', id: 8799289250, side: 'Buy', size: 78217},
        #         ]
        #     }
        #
        action = self.safe_string(message, 'action')
        table = self.safe_string(message, 'table')
        data = self.safe_value(message, 'data', [])
        # if it's an initial snapshot
        if action == 'partial':
            filter = self.safe_value(message, 'filter', {})
            marketId = self.safe_value(filter, 'symbol')
            if marketId in self.markets_by_id:
                market = self.markets_by_id[marketId]
                symbol = market['symbol']
                if table == 'orderBookL2':
                    self.orderbooks[symbol] = self.indexed_order_book()
                elif table == 'orderBookL2_25':
                    self.orderbooks[symbol] = self.indexed_order_book({}, 25)
                elif table == 'orderBook10':
                    self.orderbooks[symbol] = self.indexed_order_book({}, 10)
                orderbook = self.orderbooks[symbol]
                for i in range(0, len(data)):
                    price = self.safe_float(data[i], 'price')
                    size = self.safe_float(data[i], 'size')
                    id = self.safe_string(data[i], 'id')
                    side = self.safe_string(data[i], 'side')
                    side = 'bids' if (side == 'Buy') else 'asks'
                    bookside = orderbook[side]
                    bookside.store(price, size, id)
                messageHash = table + ':' + marketId
                client.resolve(orderbook, messageHash)
        else:
            numUpdatesByMarketId = {}
            for i in range(0, len(data)):
                marketId = self.safe_value(data[i], 'symbol')
                if marketId in self.markets_by_id:
                    if not (marketId in numUpdatesByMarketId):
                        numUpdatesByMarketId[marketId] = 0
                    numUpdatesByMarketId[marketId] = self.sum(numUpdatesByMarketId, 1)
                    market = self.markets_by_id[marketId]
                    symbol = market['symbol']
                    orderbook = self.orderbooks[symbol]
                    price = self.safe_float(data[i], 'price')
                    size = self.safe_float(data[i], 'size', 0)
                    id = self.safe_string(data[i], 'id')
                    side = self.safe_string(data[i], 'side')
                    side = 'bids' if (side == 'Buy') else 'asks'
                    bookside = orderbook[side]
                    bookside.store(price, size, id)
            marketIds = list(numUpdatesByMarketId.keys())
            for i in range(0, len(marketIds)):
                marketId = marketIds[i]
                messageHash = table + ':' + marketId
                market = self.markets_by_id[marketId]
                symbol = market['symbol']
                orderbook = self.orderbooks[symbol]
                client.resolve(orderbook, messageHash)

    def handle_system_status(self, client, message):
        #
        # todo answer the question whether handleSystemStatus should be renamed
        # and unified as handleStatus for any usage pattern that
        # involves system status and maintenance updates
        #
        #     {
        #         info: 'Welcome to the BitMEX Realtime API.',
        #         version: '2019-11-22T00:24:37.000Z',
        #         timestamp: '2019-11-23T09:02:27.771Z',
        #         docs: 'https://www.bitmex.com/app/wsAPI',
        #         limit: {remaining: 39}
        #     }
        #
        return message

    def handle_subscription_status(self, client, message):
        #
        #     {
        #         success: True,
        #         subscribe: 'orderBookL2:XBTUSD',
        #         request: {op: 'subscribe', args: ['orderBookL2:XBTUSD']}
        #     }
        #
        return message

    def handle_error_message(self, client, message):
        #
        # generic error format
        #
        #     {"error": errorMessage}
        #
        # examples
        #
        #     {
        #         "status": 429,
        #         "error": "Rate limit exceeded, retry in 1 seconds.",
        #         "meta": {"retryAfter": 1},
        #         "request": {"op": "subscribe", "args": "orderBook"},
        #     }
        #
        #     {"error": "Rate limit exceeded, retry in 29 seconds."}
        #
        error = self.safe_value(message, 'error')
        if error is not None:
            request = self.safe_value(message, 'request', {})
            args = self.safe_string(request, 'args', [])
            numArgs = len(args)
            if numArgs > 0:
                messageHash = args[0]
                broad = self.exceptions['ws']['broad']
                broadKey = self.find_broadly_matched_key(broad, error)
                exception = None
                if broadKey is None:
                    exception = ExchangeError(error)
                else:
                    exception = broad[broadKey](error)
                client.reject(exception, messageHash)
                return False
        return True

    def handle_message(self, client, message):
        #
        #     {
        #         info: 'Welcome to the BitMEX Realtime API.',
        #         version: '2019-11-22T00:24:37.000Z',
        #         timestamp: '2019-11-23T09:04:42.569Z',
        #         docs: 'https://www.bitmex.com/app/wsAPI',
        #         limit: {remaining: 38}
        #     }
        #
        #     {
        #         success: True,
        #         subscribe: 'orderBookL2:XBTUSD',
        #         request: {op: 'subscribe', args: ['orderBookL2:XBTUSD']}
        #     }
        #
        #     {
        #         table: 'orderBookL2',
        #         action: 'update',
        #         data: [
        #             {symbol: 'XBTUSD', id: 8799284800, side: 'Sell', size: 721000},
        #             {symbol: 'XBTUSD', id: 8799285100, side: 'Sell', size: 70590},
        #             {symbol: 'XBTUSD', id: 8799285550, side: 'Sell', size: 217652},
        #             {symbol: 'XBTUSD', id: 8799285850, side: 'Sell', size: 105578},
        #             {symbol: 'XBTUSD', id: 8799286350, side: 'Sell', size: 172093},
        #             {symbol: 'XBTUSD', id: 8799286650, side: 'Sell', size: 201125},
        #             {symbol: 'XBTUSD', id: 8799288950, side: 'Buy', size: 47552},
        #             {symbol: 'XBTUSD', id: 8799289250, side: 'Buy', size: 78217},
        #             {symbol: 'XBTUSD', id: 8799289700, side: 'Buy', size: 193677},
        #             {symbol: 'XBTUSD', id: 8799290000, side: 'Buy', size: 818161},
        #             {symbol: 'XBTUSD', id: 8799290500, side: 'Buy', size: 218806},
        #             {symbol: 'XBTUSD', id: 8799290800, side: 'Buy', size: 102946}
        #         ]
        #     }
        #
        if self.handle_error_message(client, message):
            table = self.safe_string(message, 'table')
            methods = {
                'orderBookL2': self.handle_order_book,
                'orderBookL2_25': self.handle_order_book,
                'orderBook10': self.handle_order_book,
                'instrument': self.handle_ticker,
                'trade': self.handle_trades,
                'tradeBin1m': self.handle_ohlcv,
                'tradeBin5m': self.handle_ohlcv,
                'tradeBin1h': self.handle_ohlcv,
                'tradeBin1d': self.handle_ohlcv,
            }
            method = self.safe_value(methods, table)
            if method is None:
                return message
            else:
                return method(client, message)
