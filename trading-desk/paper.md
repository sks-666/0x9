# How to Run a One-Person Crypto Desk With a Team of Agents and the Binance MCP

*A Design and Evaluation Protocol for Research, Backtesting, Risk, and Execution Through Role-Isolated Agents*

---

**Abstract** — A one-person trading operation has always been limited by one resource: the operator's attention. Crypto makes the limit worse, because the market never closes. A chat model does not solve this. It answers once, forgets, and stops when the window closes. This paper describes a desk built from six role-isolated agents running on the operator's existing agent system, with the Binance MCP server as the single connection to the market. A Chief of Staff orchestrates the desk. A Scout monitors news, volume and funding. A Chart Analyst scores setups from Binance candles. A Strategist turns setups into tested rules. A Risk Officer, implemented as deterministic code with veto power, approves or rejects every plan. A Trader is the only agent that can open positions. The desk runs two loops: a nightly research loop that approves strategies, and an hourly execution loop that trades them. Every live position carries a stop order resting on the exchange, and a small kill-switch monitor that runs independently of the agents can cancel and flatten. Tool access is enforced per agent, and a dedicated Binance sub-account is the hard outer limit. A strategy counts as profitable only if it stays positive after costs on data it never saw, survives a deflated Sharpe test with the number of trials counted, and holds across walk-forward windows. This paper is a **protocol**: it fixes the gates, metrics and promotion ladder *before* results are collected, and Section VII gives the reporting template the desk must fill. The claim is not that the desk finds edge. It is that the search for edge, and the discipline around it, can run without interruption under one operator.

**Index Terms** — autonomous agents, multi-agent orchestration, Model Context Protocol, Binance, crypto trading, walk-forward validation, deflated Sharpe ratio, fractional Kelly, paper trading, risk veto, pre-registration.

---

## I. Introduction: The Attention Ceiling

Every independent trader hits the same wall. The market never closes, but the trader does. In crypto this is literal: there is no session, no weekend, and no closing bell. Every opportunity needs the same chain of work: find it, read the chart, test the rule, check the risk, place the order. One person can run that chain a few times a day. A desk runs it continuously.

The usual answer has been to automate one link of the chain: a screener, an alert, a script that places orders. Each tool helps, but none of them reasons and none of them coordinates with the others. The operator stays the glue, and the operator's attention stays the ceiling.

This paper takes the operator out of routine work and keeps the operator at the top of the decision loop. The result is a desk that researches, tests and trades around the clock, and asks a human only when capital is at stake.

### A. Why a Chatbot Cannot Run a Desk

A chat model asked to trade gets a question, gives a confident answer, and stops. It has no schedule, so it cannot watch the market. It has no persistent memory, so it repeats last week's rejected idea as if it were new. It has no tools, so it describes a chart it never opened. It has no colleagues, so the same model that proposes a trade also approves it. Each of these is fatal on its own.

### B. The Six-Agent Desk

The desk has six agents with one job each. The **Chief of Staff** enforces process and is the only agent that talks to the operator. The **Scout** finds what is moving and why. The **Chart Analyst** turns stories into scored setups and watches approved strategies for signals. The **Strategist** writes exact rules and tests them on history. The **Risk Officer** can reject any plan. The **Trader** executes only plans stamped as approved. Alongside the agents runs one non-agent component, the **kill-switch monitor** (Section V.F), which can stop the desk even if every agent is down. No agent moves real capital without operator approval until a strategy has climbed the promotion ladder in Section VI.

### C. One Chatbot Versus the Six-Agent Desk

**Table I. One chatbot vs. the six-agent desk**

| Function | One chatbot | Six-agent desk |
|---|---|---|
| Market watch | Only when asked | Scout, every 30 min, 24/7 |
| Chart reading | From memory | Chart Analyst, Binance klines via MCP |
| Strategy testing | Untested | Strategist, walk-forward backtests |
| Risk control | Self-approved | Risk Officer, deterministic veto |
| Execution | Manual | Trader only; simulated fills first |
| Protection when offline | None | Stop orders resting on the exchange |
| Memory | Resets each chat | Files on disk: Desk Rules + Trade Journal |

The difference is not intelligence. It is whether every function has an owner, a schedule and a record.

### D. Scope

This document specifies the desk and the evaluation protocol. **It does not report trading results.** Section VII contains the results template, the pre-registered metrics, and the rule that numbers enter this paper only from the Trade Journal, never from an agent's summary.

### E. What This Paper Delivers

Section II specifies the desk architecture, its two loops, and its schedule. Section III gives the strategy discovery loop and its statistical gates. Section IV covers market data, the Desk Signal, protective orders and execution through Binance. Section V covers sizing, risk limits and the kill switch. Section VI covers the paper-to-live ladder and daily operation. Section VII is the evaluation protocol and results template. Section VIII lists limitations.

---

## II. Desk Architecture

### A. The Primitive: An Agent With a Role, Tools, and Files

The desk runs on the operator's existing agent system. Each agent is defined by three things:

1. **A role file**: a short prompt naming the agent's single job, its inputs, its output format, and the documents it must read first.
2. **A tool allowlist**: the exact MCP tools the agent may call. Anything not listed is unavailable to that agent.
3. **Shared files**: the Desk Rules (which only the operator edits) and the Trade Journal (append-only).

Agents do not rely on hidden model memory. All state that matters lives in files on disk that the operator can open, compare and audit.

### B. Two Loops: Research and Execution

The desk runs two loops at different speeds. Mixing them up would put the slowest agent in the path of every trade, so they are kept separate.

**The research loop (nightly)** decides *which strategies are allowed to trade*. Let $x_{[0,t]}$ be the market history up to time $t$: prices, volume, order-book depth, funding rates and news. A candidate strategy $\sigma$ is admitted to the approved book $\mathcal{A}$ only after passing every research stage in order:

$$\sigma \in \mathcal{A} \iff R_{\text{strategy}}\Big(S\big(C\big(Sc(x_{[0,t]})\big)\big)\Big) = \text{APPROVED} \tag{1a}$$

where $Sc$ is the Scout, $C$ the Chart Analyst, $S$ the Strategist and $R_{\text{strategy}}$ the Risk Officer's strategy-level review (gates, capacity, fit with the rest of the book).

**The execution loop (hourly)** decides *whether to act now* on a strategy already in the book. On each closed candle, each approved strategy $\sigma_k$ produces a signal, and the action is:

$$a_t = T\Big(R_{\text{trade}}\big(\sigma_k(x_t)\big)\Big), \qquad \sigma_k \in \mathcal{A} \tag{1b}$$

where $R_{\text{trade}}$ is the Risk Officer's trade-level check (size, heat, correlation, events, exchange filters) and $T$ is the Trader. The Strategist is not in the execution loop: it never sees a live signal, and it cannot delay one.

In both loops no stage may be skipped, and any stage may return a null action, which ends the path; the reason is written to the journal.

### C. How Much the Stages Protect: A Measured Bound

Let $f_i$ be the probability that research stage $i$ passes an idea that is in fact bad, given that the idea reached stage $i$. If the stages filtered *independently*, the probability that a bad idea reaches the approved book would be:

$$P(\text{bad strategy approved}) = \prod_{i=1}^{k} f_i \tag{2}$$

Three conditions make this equation usable:

- **The $f_i$ are measured, not assumed.** Until at least 50 ideas have passed through a stage, its $f_i$ is reported as "not yet measured", not as an illustrative number.
- **Rejected ideas are shadow-tracked.** To know whether a stage passes bad ideas, the desk must know which ideas were bad, *including the ones it rejected*. Every idea rejected at any stage is still followed on paper by the same simulator used for paper trading (Section IV.E) over its stated holding period. An idea is labelled **bad** if its shadow or real out-of-sample result after full costs is negative. Then
  $$f_i = \frac{\#\{\text{bad ideas that passed stage } i\}}{\#\{\text{bad ideas that reached stage } i\}}.$$
  Without shadow tracking only the numerator is observable and $f_i$ cannot be computed.
- **Independence is not guaranteed.** All stages read the same Binance data and often share the same model, so their errors are positively correlated. The product in (2) is therefore a *best case*. Whatever the correlation, the probability can never exceed $\min_i f_i$, the pass rate of the weakest stage. The journal reports the product, the worst case $\min_i f_i$, and the **measured joint rate**: the fraction of bad ideas that passed every stage. The desk is judged on the measured joint rate once there is enough data, and on $\min_i f_i$ before that.

This is also why the Risk Officer is built as code rather than as another model call (Section V.H): a deterministic check does not share the language model's failure modes, so it is the stage most likely to be independent of the others.

### D. Architecture Diagram

```
                              OPERATOR
                                 |
                          CHIEF OF STAFF
                                 |
 RESEARCH LOOP (nightly)
   SCOUT -> CHART ANALYST -> STRATEGIST -> RISK OFFICER -> approved book A
                                            (strategy review)
 EXECUTION LOOP (hourly)
   approved book A -> CHART ANALYST -> RISK OFFICER -> TRADER
                     (signal on close)  (trade check)    |
                                                         v
                                                  entry + resting stop
 INDEPENDENT
   KILL-SWITCH MONITOR (every minute, not an agent) -> cancel / flatten

 All components reach Binance through ONE MCP connection (one set of
 credentials). What each component can do is limited by its own tool list.

                     TRADE JOURNAL (append-only)
```

*Fig. 1. The two loops, the independent kill-switch monitor, and the single Binance MCP connection. Access is separated by per-component tool lists, not by separate credentials.*

### E. Desk Rules as Shared State

Every agent reads one document before acting: the **Desk Rules**. It fixes:

- tradable symbols (for example, a fixed list of liquid USDT spot pairs) and the instruments the desk must never trade (margin, futures, leveraged tokens, pairs listed for under 30 days);
- capital, maximum risk per trade $f_{max}$, maximum daily loss, maximum open positions, maximum holding period;
- for every approved strategy, its **planned maximum drawdown** $D_{plan}$: the largest peak-to-trough fall in the strategy's equity that the operator accepts. It is set before the walk-forward test, and it is used by the crash gate (Table III) and by sizing;
- the setup-score weights (Section III.B) and the gate thresholds (Table III);
- the cost model (Section III.C);
- two behavioural rules: every claim carries a source and a timestamp, and no agent may invent a price, a number or a result. A number with no journal or MCP source is treated as false.

The Desk Rules are versioned. Every journal entry records the Desk Rules version it was produced under, so a change of rules never silently mixes two regimes in one result.

### F. Routines and the 24-Hour Schedule

Autonomy comes from scheduled runs. A scheduler on the operator's system (cron, a hosted scheduled job, or the agent system's own routine feature) wakes the Chief of Staff, which dispatches the other agents. The kill-switch monitor has its **own** scheduler and does not depend on the agents or on the Chief of Staff. Because crypto trades 24/7, all times are **UTC** and "day" means the UTC calendar day.

**Table II. Routine schedule (UTC)**

| Component | Loop | Schedule | Output |
|---|---|---|---|
| Scout | Research | Every 30 min | Ranked findings with sources |
| Chart Analyst | Research | 23:30 nightly | Setups scored 7 or above |
| Strategist | Research | 00:30 nightly | Tested strategies for review |
| Risk Officer | Research | 02:00 nightly | Strategies APPROVED / VETO |
| Chart Analyst | Execution | Every hour, at candle close + 1 min | Signals from approved strategies |
| Risk Officer | Execution | On every signal | Trade APPROVED / VETO |
| Trader | Execution | On approval; every 15 min for reconciliation | Orders, stops, fills |
| Kill-switch monitor | Independent | Every minute | Cancel / flatten / pause |
| Chief of Staff | Both | 07:00 and 19:00 daily; Sunday 18:00 | Briefs and weekly review |

**The schedule sets the desk's speed limit.** Signals are computed on hourly candle closes, and an approved signal goes straight to the Risk Officer and Trader, so an order is normally placed within a few minutes of the candle close. But the move that triggered the signal may have started at any point during that hour, so the desk can be up to about an hour behind the start of a move. It therefore only trades strategies whose expected edge lasts hours to days, and the Strategist must reject any hypothesis whose expected holding period is shorter than 4 hours.

### G. The Handoff Protocol

Agents communicate in one fixed format:

```
TICKER:    BTCUSDT
IDEA:      Breakout above 20-bar high on 1h, trend stacked
EVIDENCE:  spot_klines 1h, candle closed 14:00Z; score 7.6 (components ...)
NEXT STEP: Strategist to backtest rule DS-v3 on 3y of 1h data
```

A fixed format removes ambiguity and makes every message machine-checkable. An idea without evidence goes back to its sender. A trade that reaches the Trader without an `APPROVED` stamp from the Risk Officer, matching the signal's ID, the strategy's ID and the Desk Rules version, is refused.

### H. The Trade Journal

The desk writes every idea, score, plan, risk decision, simulated or real order, stop order, fill, kill-switch event, and lesson to an append-only Trade Journal (one record per line, never edited in place). The journal is:

- the **audit trail**: any number in a brief must cite a journal record;
- the **calibration source**: the $f_i$ in (2), the win rate and payoff in (15), the streak limit in (20) and the consistency test in (19) are all computed from it;
- the **shadow book**: the paper outcome of every rejected idea (Section II.C);
- the **graveyard**: every tested configuration is recorded with a fingerprint of its rule **and its out-of-sample statistics** (per-period Sharpe ratio, number of observations, skewness, kurtosis). This lets the desk avoid retesting an idea by accident, and gives the trial count $N$ and the Sharpe variance $V[\widehat{SR}_n]$ needed in Section III.E. A retest is a new trial and is counted.

---

## III. The Strategy Discovery Loop

### A. From Signal to Hypothesis

Discovery starts with the Scout, which records every market event with a source and a timestamp: unusual 24-hour volume or price change from Binance tickers, extreme perpetual funding rates (read-only), and news. Each night the Strategist turns the day's findings into testable hypotheses. A hypothesis is admissible only if it names:

1. its **edge** (what it exploits),
2. the **reason** it should work,
3. the **counterparty** who loses when it does,
4. its **expected holding period**, which must be at least 4 hours (Section II.F),
5. its **expected trade frequency**, so the time needed to collect enough trades (Section VI.A) is known before testing.

Ideas without a stated edge are discarded before any computation is spent on them.

### B. The Setup Score

The Chart Analyst scores each candidate on five components computed from Binance candles: trend alignment, distance to a key level, momentum, relative volume, and catalyst strength. Each component $s_j$ is scored from 0 to 10 and combined with fixed weights $w_j$:

$$S = \sum_{j=1}^{5} w_j s_j, \qquad \sum_{j=1}^{5} w_j = 1, \qquad s_j \in [0, 10] \tag{3}$$

Only setups with $S \ge 7$ go to the Strategist. The weights live in the Desk Rules so the score cannot drift toward whatever the agent finds persuasive that day. The four chart-based components are computed by code from the candles. Only catalyst strength is a model judgment, and the Desk Rules must give it the smallest weight.

### C. Backtest Statistics and Costs

Every strategy is backtested on at least three years of Binance history for its symbol. Symbols with less than two years of history are not tradable; between two and three years, the full history is used. Gross results mean nothing without costs, so each of the $n$ round trips is charged a cost $c_{rt}$:

$$R_{net} \approx R_{gross} - n \cdot c_{rt} \tag{4}$$

(For compounded returns, the cost is deducted from each trade's return before compounding; (4) is the simple-sum approximation.)

For Binance spot, $c_{rt}$ is **two taker fees plus estimated spread and slippage**. The fee comes from the account's actual fee tier (the base spot tier is 0.10% per side, lower with BNB discounts or VIP tiers; the Desk Rules record the tier in use). Slippage is estimated from order-book depth at the planned order size, not assumed to be zero.

Risk-adjusted performance is the Sharpe ratio of daily net returns $r$. **Crypto trades every day of the year, so the annualisation factor is $\sqrt{365}$, not the equity-market $\sqrt{252}$:**

$$SR_{ann} = \frac{\bar{r}}{\sigma_r}\sqrt{365} \tag{5}$$

Downside is measured by the maximum drawdown of the equity curve $V_t$:

$$MDD = \max_t \frac{P_t - V_t}{P_t}, \qquad P_t = \max_{s \le t} V_s \tag{6}$$

### D. Walk-Forward Validation

One backtest describes one path through history. The desk instead splits the data into rolling windows, fits the rules on each training window, and evaluates them untouched on the following test window. Rules are never adjusted after the test window has been seen. A strategy that works in only one window is classed as luck, not edge.

Because crypto regimes change fast, windows are short (for example, 12 months train, 3 months test, rolled forward by 3 months). At least one **test** window must contain a fall of 30% or more in BTC, so that every surviving strategy has been tested out of sample through a crash. If the available history has no such fall after the first training window, the strategy cannot pass the crash gate and is not approved.

### E. The Multiple-Testing Problem

An agent that tests ten ideas a night tests over three thousand a year, and some will look excellent by chance. Among $N$ independent strategies with no true edge, the best observed Sharpe ratio grows roughly as:

$$E[\max SR] \approx \sigma_{SR}\sqrt{2\ln N} \tag{7}$$

The desk evaluates every survivor with the **deflated Sharpe ratio** (Bailey and López de Prado), with two details that are commonly gotten wrong:

1. **Use the per-period Sharpe ratio, not the annualised one.** In (8), $\widehat{SR}$ is the Sharpe ratio of the *daily* returns (no $\sqrt{365}$), and $T$ is the number of daily observations. Mixing an annualised $SR$ with a daily $T$ overstates significance by a large factor.
2. **Count trials honestly.** $N$ is the number of *distinct* configurations tested for this family of strategies, taken from the graveyard, including every failed variant and every retest. Highly similar variants can be grouped into an effective $N$, but the grouping method must be written down before testing.

The benchmark $SR_0$ is the expected maximum Sharpe under $N$ trials, using the exact form rather than (7):

$$SR_0 = \sqrt{V[\widehat{SR}_n]}\left((1-\gamma)\,\Phi^{-1}\!\left(1-\frac{1}{N}\right) + \gamma\,\Phi^{-1}\!\left(1-\frac{1}{Ne}\right)\right)$$

where $V[\widehat{SR}_n]$ is the variance of the per-period Sharpe ratios of the $N$ trials (read from the graveyard), $\gamma \approx 0.5772$ is the Euler–Mascheroni constant and $e$ is Euler's number. Then:

$$DSR = \Phi\!\left(\frac{(\widehat{SR} - SR_0)\sqrt{T-1}}{\sqrt{1 - \hat\gamma_3\widehat{SR} + \frac{\hat\gamma_4 - 1}{4}\widehat{SR}^2}}\right) \tag{8}$$

where $\hat\gamma_3$ is the skewness and $\hat\gamma_4$ the (non-excess) kurtosis of daily returns, and $\Phi$ is the standard normal distribution function. Crypto returns are heavily fat-tailed, so the kurtosis term matters and must never be dropped.

### F. The Gates

A strategy is called profitable in this paper only if it passes every gate in Table III. Failing any single gate sends it to the graveyard.

**Table III. Statistical gates for a profitable strategy**

| Gate | Threshold | Purpose |
|---|---|---|
| Trade count | $n \ge 30$ out-of-sample trades | Statistical meaning |
| Out-of-sample | $R_{net} > 0$ after full costs | Profit after costs |
| Deflated Sharpe | $DSR \ge 0.95$ with graveyard $N$ | Luck correction |
| Walk-forward | $\ge 70\%$ of test windows positive | Stability |
| Crash window | Drawdown in the crash test window $\le 1.5 \times D_{plan}$ | Survives a crash |
| Latest year | Positive | Current regime |
| Parameter plateau | Neighbours within 20% still pass | No lucky setting |
| Noise test | 1-bar delay and $2\times$ slippage still positive | Real-world friction |
| Capacity | Planned size $\le 1\%$ of median hourly volume | Fill is realistic |
| Frequency | Expected $\ge 20$ trades within 8 weeks | Can climb the ladder |

---

## IV. Market Data, Signals, and Execution Through Binance

### A. The Binance MCP as the Only Market Interface

The desk reaches the market through one channel: the Binance MCP server, which exposes the official Binance REST endpoints as tools. It is **one connection with one set of credentials**. The desk uses two groups of its tools:

- **Market data (read-only)**: candles (`spot_klines`), 24-hour tickers (`spot_ticker24hr`), prices (`spot_tickerPrice`), order-book depth (`spot_depth`), exchange rules (`spot_exchangeInfo`), and, read-only, perpetual funding data used as a sentiment input.
- **Account and orders**: balances (`spot_getAccount`), open orders and trade history (`spot_getOpenOrders`, `spot_getOrder`, `spot_myTrades`), and order placement and cancellation (`spot_newOrder`, `spot_deleteOrder`, `spot_deleteOpenOrders`).

**The Binance MCP talks to the real exchange. There is no built-in paper mode.** Every order tool call is a real order. Paper trading is therefore implemented by the desk itself (Section IV.F), not by the broker.

### B. Tool Isolation by Component

Because all components share one connection, the only thing separating "can read prices" from "can place orders" is each component's tool list. Each list is set in the component's role file or configuration. A second, independent **deny list** in the agent system's permission settings blocks the dangerous tool families for every component without exception: all wallet transfer and withdrawal tools (including universal transfer), all sub-account transfer tools, all futures and margin tools, and all convert tools. If a role file is edited by mistake, the deny list still holds.

**Table IV. Tool access by component**

| Component | Market data | Account / orders |
|---|---|---|
| Scout | Tickers, funding (read), news source | None |
| Chart Analyst | Klines, depth | None |
| Strategist | Klines (history) | None |
| Risk Officer | Prices, depth, exchange rules | Account and open orders: **read only** |
| Trader | Prices, depth, exchange rules | `spot_newOrder` (entries, stops, exits), `spot_deleteOrder`, order and account reads |
| Kill-switch monitor | Prices, klines | `spot_deleteOpenOrders`; `spot_newOrder` **restricted to market SELL of an existing position**; account reads |
| Chief of Staff | Prices | Account: **read only** |
| *All components* | — | **Denied:** wallet transfer / withdraw / universal transfer, sub-account transfer, futures, margin, convert |

The Risk Officer decides; it never acts on the account. When it vetoes a live position or a kill-switch condition is met, the action is carried out by the Trader (for a single position) or by the kill-switch monitor (for the desk).

### C. The Desk Signal

The Chart Analyst computes the Desk Signal from Binance candles (hourly by default). It combines four parts.

**Trend** is measured by an exponential moving average ribbon with lengths $n \in \{8, 21, 50, 200\}$:

$$EMA_t = \alpha P_t + (1-\alpha)EMA_{t-1}, \qquad \alpha = \frac{2}{n+1} \tag{9}$$

The trend is bullish only when the ribbon is fully stacked, $EMA_8 > EMA_{21} > EMA_{50} > EMA_{200}$, and bearish in the reverse order.

**Momentum** is the 14-period relative strength index:

$$RSI_t = 100 - \frac{100}{1 + RS_t}, \qquad RS_t = \frac{\bar G_{14}}{\bar L_{14}} \tag{10}$$

where $\bar G_{14}$ and $\bar L_{14}$ are Wilder-smoothed average gain and loss.

**Breakout with volume.** A buy signal needs a close above the prior 20-bar high, on expanded volume, confirmed by momentum and a bullish trend:

$$BUY_t = \mathbb{1}\left[C_t > \max H_{t-20:t-1} \;\wedge\; V_t \ge 1.5\,\bar V_{t-20:t-1} \;\wedge\; RSI_t > 50 \;\wedge\; \text{trend}_t = \text{bull}\right] \tag{11}$$

Both the 20-bar high and the 20-bar average volume are taken over the **previous** 20 bars, excluding the current bar, so the current bar is compared against history it is not part of.

**Volatility filter.** Signals are suppressed in disorderly markets. With true range $TR_t = \max(H_t - L_t,\, |H_t - C_{t-1}|,\, |L_t - C_{t-1}|)$, the filter blocks every signal when:

$$ATR_{14,t} > Q_{0.90}\left(ATR_{14}\ \text{over the previous 100 bars}\right) \tag{12}$$

**Spot cannot short.** On Binance spot the bearish mirror of (11) is not a short entry. It is an **exit signal** for an open long, or a reason to stay flat. Short strategies are out of scope (futures and margin are denied in Table IV).

**Look-ahead rule.** Signals are computed only on **closed** candles. The most recent candle returned by `spot_klines` is usually still forming and must be dropped before any calculation. The backtest applies the same rule, and the noise test in Table III adds one more bar of delay on top.

**Signal frequency.** Equation (11) requires four conditions at once and is further filtered by (12), so it fires rarely on any one symbol. A strategy built on it is usually run across several symbols to collect enough trades; the Frequency gate in Table III checks this before approval.

*Listing 1. Desk Signal core logic (reference specification).*

```text
input: closed 1h candles (open, high, low, close, volume), oldest first
       drop the last candle if its close time is in the future

e8, e21, e50, e200 = EMA(close, 8/21/50/200)
bull   = e8 > e21 > e50 > e200
rsi    = RSI_Wilder(close, 14)
hi20   = max(high[t-20 .. t-1])
vol20  = mean(volume[t-20 .. t-1])
atr    = ATR_Wilder(14)
calm   = atr[t] <= percentile_90(atr[t-100 .. t-1])

BUY  = bull and close[t] > hi20 and volume[t] >= 1.5*vol20 and rsi > 50 and calm
EXIT = (not bull) or close[t] < min(low[t-20 .. t-1]) or target hit
       (the stop itself rests on the exchange; see Section IV.E)
```

### D. Exchange Rules Before Every Order

Binance rejects orders that break a symbol's filters. Before any order, simulated or real, entry or stop, the Trader reads `spot_exchangeInfo` for the symbol and:

- rounds quantity **down** to the lot step size,
- rounds price to the tick size,
- checks the order value is at or above the minimum notional; if the correctly sized order is below it, the trade is **skipped**, never sized up.

The paper simulator enforces the same filters, so a strategy cannot look good on paper with orders the exchange would refuse.

### E. Protective Orders Resting on the Exchange

Stops that exist only in the agents' logic protect nothing when the agents are not running. A scheduler failure, a disconnected MCP connector or a model outage would leave positions unprotected. Therefore:

1. **Every live entry is followed immediately by a stop order placed on Binance**: a stop-loss order (for example `STOP_LOSS_LIMIT` through `spot_newOrder`), or an OCO order (stop and take-profit in one) where the connector exposes it. The stop price is the planned stop $P_s$ from Section V.A.
2. **No stop, no position.** If the stop order is rejected or cannot be confirmed with `spot_getOpenOrders` within 60 seconds of the entry fill, the Trader closes the position at market and logs a rule breach.
3. **The limit price of a stop-limit order is set below the stop price** by a buffer the Desk Rules fix (for example, one ATR or 1%, whichever is larger), so that it still fills in a fast move. A stop-limit can still fail to fill in a gap through the limit; the kill-switch monitor (Section V.F) is the backup.
4. **Every 15 minutes the Trader reconciles**: every open position in the journal must have a matching live stop order on the exchange, and every stop order must match a position. Any mismatch is a reconciliation failure (Table V).

### F. Paper Mode Without a Broker Paper Account

Because the Binance MCP has no paper mode, the desk runs paper trading in three layers:

1. **Simulated fills.** In paper rungs, the Trader does not call `spot_newOrder`. It reads `spot_depth` at decision time, computes the fill price by walking the order book for the planned size, applies the real fee tier, and writes a simulated fill to the journal marked `mode: paper`. Simulated stops trigger when a later closed candle's low reaches the stop price, and fill at the stop price minus the same slippage estimate.
2. **No order tools in paper rungs.** During paper rungs the Trader's tool list does not include `spot_newOrder`. Paper mode is enforced by configuration, not by the Trader remembering it is in paper mode.
3. **An empty live account.** The trading sub-account (Section V.G) holds no funds until the strategy reaches rung 3. Even a mis-configured order call fails for insufficient balance.

The same simulator follows the rejected ideas in the shadow book (Section II.C).

---

## V. Position Sizing and Risk

### A. Fixed-Fractional Sizing

A strategy that passes every gate can still destroy an account if it is sized badly. The desk sizes every position so that the loss at the stop equals a fixed fraction $f$ of current equity $E$. For entry price $P_e$ and stop price $P_s$, the quantity is:

$$q = \frac{f \cdot E}{|P_e - P_s|} \tag{13}$$

then rounded down to the symbol's step size (Section IV.D). Here $E$ is the equity of the trading sub-account, so at rung 3 (10% of planned capital) every trade is automatically 10% of its planned size. With $f = 1\%$ and $E = \$10{,}000$, a trade with a \$2 stop distance buys 50 units, and the worst planned loss is \$100 whatever the price of the asset, *before* fees and before any slippage past the stop (Sections IV.E and V.E).

### B. Volatility Scaling

Stops are placed at a multiple $k$ of the average true range, so stop distance widens automatically when markets get more volatile. Substituting into (13):

$$q = \frac{f \cdot E}{k \cdot ATR_{14}} \tag{14}$$

Position size therefore shrinks as volatility rises, without any judgment call by the agents.

### C. Fractional Kelly as an Upper Bound

For a strategy with out-of-sample win rate $p$ and payoff ratio $b$ (average win divided by average loss), the Kelly fraction is:

$$f^* = p - \frac{1-p}{b} \tag{15}$$

Full Kelly is too aggressive for estimated inputs, so the desk uses a quarter of it, capped by the Desk Rules:

$$f = \min\left(\frac{f^*}{4},\, f_{max}\right) \tag{16}$$

For $p = 0.55$ and $b = 1.5$, $f^* = 0.25$ and quarter Kelly is 6.25%, but the 1% cap binds. Kelly is a ceiling, never a target. Its inputs come only from out-of-sample and paper trades in the journal, and only once there are at least 30 of them; before that, $f$ is set to half of $f_{max}$. If $f^* \le 0$, the strategy has no edge on its own numbers and is demoted.

### D. Correlation and Portfolio Heat

Two positions in correlated assets are one bet sized twice. In crypto this is the normal case: most large coins move with BTC. With position weights $w$ and return covariance matrix $\Sigma$, portfolio volatility is:

$$\sigma_p = \sqrt{w^\top \Sigma\, w} \tag{17}$$

The Risk Officer groups positions into **clusters**: any two symbols whose 30-day correlation of daily returns is above 0.7 are in the same cluster. Two limits apply:

$$\sum_{i \in \text{cluster } c} f_i \le f_{max} \quad \text{for every cluster } c, \qquad H = \sum_{i=1}^{n} f_i \le H_{max} = 5\% \tag{18}$$

The first limit means a cluster of correlated positions together risks no more than one full-size trade; a new position in an occupied cluster is sized down to the remaining room, or skipped if that is below the minimum notional. The second limits total open risk, the portfolio heat $H$. Correlations are recomputed daily, because crypto correlations jump toward 1 in sell-offs, which is exactly when the limit matters most.

### E. Event Risk

Crypto has no earnings dates, but it has scheduled events that cause jumps no stop can fully protect against. The Risk Officer blocks new positions in the 2 hours before and after:

- US central bank (FOMC) decisions and US CPI releases,
- large scheduled token unlocks for the symbol,
- announced Binance maintenance windows, and any delisting or monitoring-tag notice for the symbol (which also forces an exit).

It also flags every open position whose holding period crosses one of these events.

### F. The Kill Switch

Some failures require the desk to stop. The kill switch is run by the **kill-switch monitor**, a small deterministic program that is not an agent, has its own scheduler, runs every minute, and holds only the tools in Table IV. It does not need the agents, the Chief of Staff or the model to be working. It reads the exchange directly and acts on the triggers in Table V.

The thresholds for the losing streak and strategy drift are derived from each strategy's own statistics rather than fixed:

**Losing-streak limit.** With out-of-sample win rate $p$, the expected number of losing streaks of length $L$ in the next $n$ trades is about $n\,p\,(1-p)^L$. The desk pauses a strategy only at a streak long enough that it should happen by chance less than once in twenty windows of 50 trades:

$$L_{max} = \left\lceil \frac{\ln\!\big(\alpha / (n\,p)\big)}{\ln(1-p)} \right\rceil, \qquad \alpha = 0.05,\ n = 50 \tag{20}$$

For $p = 0.55$ this gives $L_{max} = 8$. A fixed limit of 3 losses would fire two or three times in every 50 trades of a healthy strategy and stop it for no reason.

**Strategy drift.** From the strategy's out-of-sample trades, the desk draws 10,000 bootstrap paths with the same number of trades as the live record so far (resampling trades with replacement). If the live cumulative return is below the 5th percentile of those paths, the strategy is performing worse than its own history would explain, and it is demoted.

**Table V. Kill switch triggers and how the desk resumes**

| Trigger | Threshold | Action | Resume |
|---|---|---|---|
| Daily loss | $\ge 2\%$ of equity in a UTC day | Flatten, pause desk | Next UTC day, after operator acknowledges |
| Losing streak | $L_{max}$ losses in a row, eq. (20) | Pause that strategy | Operator review; back to rung 2 if cause unknown |
| Stale data | No price update for 5 min, or a missed hourly candle | Cancel entries (stops stay) | Automatic when fresh data returns for 15 min |
| Missing stop | Position without a live stop order | Flatten that position | After cause is logged |
| Slippage | Fill $> 0.5\%$ from plan | Pause strategy, review | Operator review |
| Reconciliation | Journal position $\ne$ exchange balance or stop orders | Freeze desk | Operator only, after the journal is corrected |
| API errors | 3 consecutive order or auth errors | Freeze desk | Operator only |
| Rule breach | Any component acts outside the Desk Rules | Freeze desk | Operator only, after Desk Rules review |
| Strategy drift | Live result below bootstrap 5th percentile | Demote strategy | Strategy restarts at rung 1 |

On stale data the monitor cancels pending **entry** orders only. Resting stop orders are never cancelled for stale data, because they are the protection that still works when data does not. Only the operator can lift a freeze.

### G. Layered Enforcement

Every limit is enforced in three independent layers:

1. **Desk Rules** read by every agent: a rule.
2. **Tool isolation**: the tool list per component plus the global deny list (Table IV), so only the Trader can open positions and nobody can move funds, use futures, margin or convert.
3. **Exchange-side limits**, which are the actual wall:
   - The desk trades from a **dedicated Binance sub-account** funded only with the capital for the current rung. The worst possible loss is the sub-account balance.
   - Credentials behind the Binance MCP allow **spot trading only**: withdrawals **off**, universal transfer **off**, futures and margin **off**. If the operator uses their own API key, IP whitelisting is **on**.
   - If the Binance MCP is a hosted connector that logs in on the operator's behalf rather than using a key the operator created, the operator may not be able to set these permissions directly. The operator must check which permissions the connector was granted. If withdrawal or transfer rights cannot be ruled out, the dedicated, minimally funded sub-account is the only wall that can be relied on.
   - The operator moves capital *into* the sub-account by hand at each promotion. No component can move it.

A prompt is a rule. A permission setting is a fence. An exchange setting is a wall.

### H. The Risk Officer Is Code

The Risk Officer's checks (sizing, heat, cluster limits, event windows, exchange filters, gate results) are deterministic functions of the plan, the account state and the Desk Rules. A language model may write the human-readable reason, but **the approve/veto decision comes from code** and is the same every time for the same inputs. This keeps the veto from being talked around by a persuasive plan, and makes it the stage most independent of the others in (2).

---

## VI. From Paper to Live Capital

### A. The Promotion Ladder

Most automated systems fail at one transition: the move from simulated to real capital. The desk never makes that move in one step. Every strategy climbs the five rungs in Table VI. Rungs advance on **trade count and clean behaviour**, not on the calendar, with a minimum time per rung.

**Table VI. The paper-to-live promotion ladder**

| Rung | Condition to advance | Capital | Order tools |
|---|---|---|---|
| 1. Paper | $\ge 20$ trades and $\ge 2$ weeks, all logged | Simulated | Removed |
| 2. Match | Consistency test, eq. (19), on $\ge 20$ new paper trades | Simulated | Removed |
| 3. Live tiny | 20 trades with clean fills, live stops, no breaches | 10% of plan in sub-account | Enabled |
| 4. Approval gate | Operator approves each order, 20 trades | Planned size | Enabled |
| 5. Full rules | 4 weeks of clean journal | Planned size | Enabled |

**Failure rules.** A failure at rung 1 or 2 sends the strategy back to the Strategist: it has not worked even on paper, so the research is wrong, not the execution. A failure at rungs 3 to 5 drops the strategy one rung. A strategy that has not reached 20 trades at its current rung within 8 weeks is retired as too rare to evaluate.

### B. The Consistency Test

Rung 2 asks whether paper results agree with the backtest. With $n$ paper trades of mean return $\bar r_p$, and a backtest mean $\bar r_b$ with per-trade standard deviation $\sigma_b$, the desk computes:

$$z = \frac{\bar r_p - \bar r_b}{\sigma_b / \sqrt{n}} \tag{19}$$

A strategy advances only when $n \ge 20$ and $z > -2$. A strongly negative $z$ means live conditions are destroying the edge the backtest promised, usually through costs, latency or a changed regime. With $n$ as small as 20 this test can only catch large failures. It is a minimum check, not proof of consistency, and rungs 3 to 5 exist to keep testing with real fills.

### C. Daily Operation

The operator's day comes down to two messages and a set of approvals. The morning brief summarises the market, the ranked findings, any strategies approved or rejected overnight, and every veto and kill-switch event with its reason. Live orders at rung 4 arrive as a fixed card: symbol, side, entry, stop, target, size, risk, and rationale, each with its journal reference. **Silence is treated as rejection.** Each Sunday the Chief of Staff compares live results with backtests, demotes decaying strategies, and archives failed ideas in the graveyard so they are never retested by accident.

---

## VII. Evaluation Protocol and Results Template

This section is fixed **before** results are collected. Results enter it only from the Trade Journal, and the Chief of Staff may not write a number here that it cannot cite to a journal record.

### A. Pre-Registered Metrics

For every strategy that reaches rung 2 or above, report:

**Table VII. Strategy results (to be filled from the journal)**

| Strategy | Symbols / TF | Trials $N$ | OOS trades | $R_{net}$ | $SR_{ann}$ | $DSR$ | MDD vs $D_{plan}$ | WF windows + | Rung |
|---|---|---|---|---|---|---|---|---|---|
| — | — | — | — | — | — | — | — | — | — |

For the research loop as a whole, report the funnel. "Bad" means a negative out-of-sample or shadow result after full costs (Section II.C).

**Table VIII. Research funnel (to be filled from the journal and shadow book)**

| Stage | Ideas in | Of which bad | Bad that passed | Measured $f_i$ |
|---|---|---|---|---|
| Scout → Chart Analyst | — | — | — | — |
| Chart Analyst → Strategist | — | — | — | — |
| Strategist (gates) | — | — | — | — |
| Risk Officer (strategy review) | — | — | — | — |
| **Joint (measured)** | — | — | — | — |
| Product bound, eq. (2) | | | | — |
| Worst case $\min f_i$ | | | | — |

### B. Required Figures

1. Out-of-sample equity curve per strategy, with walk-forward window boundaries and the crash window marked.
2. Paper vs. backtest per-trade return distribution (the input to (19)).
3. Live fill price vs. planned price per trade, for entries and for stops.
4. Kill-switch events over time, by trigger.

### C. Reporting Rules

- Report **every** strategy that reached rung 2, including failures, not only the winners.
- Report the total number of hypotheses tested in the period, not only those that passed.
- Report results net of actual fees paid, taken from `spot_myTrades`, not from the cost model.
- If no strategy has passed all gates yet, say so. "No edge found in $N$ trials" is a valid and useful result.

---

## VIII. Limitations

- **Not a high-frequency system.** Signals use hourly closes and the desk can lag a move by up to about an hour (Section II.F), so edges that decay within minutes are out of reach by design.
- **Spot long-only.** Futures, margin and shorting are denied. The desk cannot profit from falling markets except by staying flat.
- **Stops are not guarantees.** A stop-limit order can fail to fill in a gap through its limit price, and a market exit in a crash can fill far from plan. Planned losses in Section V.A are planned, not guaranteed.
- **No broker paper mode.** Paper fills are simulated from order-book snapshots and candle lows. They miss queue position, partial fills and market impact beyond the visible book. Rungs 3 to 5 exist to catch what simulation misses.
- **Shadow outcomes are estimates.** Rejected ideas are only ever traded on paper, so the measured $f_i$ inherit the simulator's errors.
- **Slow promotion.** With rare signals and trade-count rules, a strategy may take months to reach full size. This is intended: speed of promotion is traded for confidence.
- **Model routing and determinism.** The language models behind the agents can change between runs. This is why every decision that matters for capital (gates, sizing, veto, kill switch) is code, and why the models only propose and explain.
- **Single connection.** All components share one Binance MCP connection and one set of credentials. Tool lists separate what they can do, but a failure or compromise of the connection affects all of them; the sub-account limits the damage.
- **Exchange and connector risk.** The desk depends on Binance being available and the MCP connection working. A disconnected connector triggers the stale-data and API-error kill switches, and resting stops remain on the exchange.
- **Regulatory and account restrictions.** Binance availability and permitted products vary by country. The operator is responsible for using an account and products that are legal for them.
- **Statistics are not guarantees.** The gates reduce the chance of trading on luck. They cannot guarantee future profit, and no architecture can give edge to a strategy that has none.

---

## IX. Conclusion

A hedge fund has never been defined by its building or its headcount. It is defined by a process: research, testing, risk control and disciplined execution, running without interruption. This paper shows how that process can be assigned to a team of role-isolated agents on an existing agent system, with the Binance MCP as the only route to the market. A nightly research loop decides what may trade and an hourly execution loop decides when. Deterministic code holds the veto, stop orders rest on the exchange, an independent monitor can stop everything, and the sub-account is the final wall. Each component has one job, one schedule and one record, and the operator keeps authority over capital. The edge still has to be found. The difference is that the search no longer stops when the operator does, and every claim the desk makes can be traced back to the journal.

---

## References

1. Model Context Protocol, "Specification," modelcontextprotocol.io, 2025.
2. Binance, "Binance Spot API documentation" (market data, order types including STOP_LOSS_LIMIT and OCO, symbol filters, API key permissions), developers.binance.com, accessed 2026.
3. Binance, "Binance MCP server," tool reference as exposed by the operator's MCP connector, accessed 2026.
4. D. H. Bailey and M. López de Prado, "The deflated Sharpe ratio: Correcting for selection bias, backtest overfitting, and non-normality," *J. Portfolio Management*, vol. 40, no. 5, pp. 94–107, 2014.
5. D. H. Bailey, J. M. Borwein, M. López de Prado, and Q. J. Zhu, "Pseudo-mathematics and financial charlatanism: The effects of backtest overfitting on out-of-sample performance," *Notices of the AMS*, vol. 61, no. 5, pp. 458–471, 2014.
6. M. López de Prado, *Advances in Financial Machine Learning*. Hoboken, NJ, USA: Wiley, 2018.
7. J. L. Kelly, Jr., "A new interpretation of information rate," *Bell System Technical Journal*, vol. 35, no. 4, pp. 917–926, 1956.
8. W. F. Sharpe, "The Sharpe ratio," *J. Portfolio Management*, vol. 21, no. 1, pp. 49–58, 1994.
9. J. W. Wilder, *New Concepts in Technical Trading Systems*. Greensboro, NC, USA: Trend Research, 1978.
10. R. Pardo, *The Evaluation and Optimization of Trading Strategies*, 2nd ed. Hoboken, NJ, USA: Wiley, 2008.
