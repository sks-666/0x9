---
title: "How to Run a One-Person Crypto Desk With a Team of Agents and the Binance MCP"
subtitle: "A Design and Evaluation Protocol for Research, Backtesting, Risk, and Execution Through Role-Isolated Agents"
---

# How to Run a One-Person Crypto Desk With a Team of Agents and the Binance MCP

*A Design and Evaluation Protocol for Research, Backtesting, Risk, and Execution Through Role-Isolated Agents*

---

**Abstract** — A one-person trading operation has always been limited by one resource: the operator's attention. Crypto makes the limit worse, because the market never closes. A chat model does not solve this. It answers once, forgets, and stops when the window closes. This paper describes a desk built from six role-isolated agents running on the operator's existing agent system, with the Binance MCP server as the single connection to the market. A Chief of Staff orchestrates the desk. A Scout monitors news, volume and funding. A Chart Analyst scores setups from Binance candles. A Strategist turns setups into tested rules. A Risk Officer, implemented as deterministic code with veto power, approves or rejects every plan. A Trader is the only agent that can reach order tools. Each agent's tool access is enforced by an allowlist and by a deny list, and the Binance API key and sub-account act as a hard outer limit. A strategy counts as profitable only if it stays positive after costs on data it never saw, survives a deflated Sharpe test with the number of trials counted, and holds across walk-forward windows. This paper is a **protocol**: it fixes the gates, metrics and promotion ladder *before* results are collected, and Section VII gives the reporting template the desk must fill. The claim is not that the desk finds edge. It is that the search for edge, and the discipline around it, can run without interruption under one operator.

**Index Terms** — autonomous agents, multi-agent orchestration, Model Context Protocol, Binance, crypto trading, walk-forward validation, deflated Sharpe ratio, fractional Kelly, paper trading, risk veto, pre-registration.

---

## I. Introduction: The Attention Ceiling

Every independent trader hits the same wall. The market never closes, but the trader does. In crypto this is literal: there is no session, no weekend, and no overnight gap that a closing bell protects you from. Every opportunity needs the same chain of work: find it, read the chart, test the rule, check the risk, place the order. One person can run that chain a few times a day. A desk runs it continuously.

The usual answer has been to automate one link of the chain: a screener, an alert, a script that places orders. Each tool helps, but none of them reasons and none of them coordinates with the others. The operator stays the glue, and the operator's attention stays the ceiling.

This paper takes the operator out of routine work and keeps the operator at the top of the decision loop. The result is a desk that researches, tests and trades around the clock, and asks a human only when capital is at stake.

### A. Why a Chatbot Cannot Run a Desk

A chat model asked to trade gets a question, gives a confident answer, and stops. It has no schedule, so it cannot watch the market. It has no persistent memory, so it repeats last week's rejected idea as if it were new. It has no tools, so it describes a chart it never opened. It has no colleagues, so the same model that proposes a trade also approves it. Each of these is fatal on its own.

### B. The Six-Agent Desk

The desk has six agents with one job each. The **Chief of Staff** enforces process and is the only agent that talks to the operator. The **Scout** finds what is moving and why. The **Chart Analyst** turns stories into scored setups. The **Strategist** writes exact rules and tests them on history. The **Risk Officer** can reject any plan. The **Trader** executes only plans stamped as approved. No agent moves real capital without operator approval until a strategy has climbed the promotion ladder in Section VI.

### C. One Chatbot Versus the Six-Agent Desk

**Table I. One chatbot vs. the six-agent desk**

| Function | One chatbot | Six-agent desk |
|---|---|---|
| Market watch | Only when asked | Scout, every 30 min, 24/7 |
| Chart reading | From memory | Chart Analyst, Binance klines via MCP |
| Strategy testing | Untested | Strategist, walk-forward backtests |
| Risk control | Self-approved | Risk Officer, deterministic veto |
| Execution | Manual | Trader only; simulated fills first |
| Memory | Resets each chat | Files on disk: Desk Rules + Trade Journal |

The difference is not intelligence. It is whether every function has an owner, a schedule and a record.

### D. Scope and Honesty Statement

This document specifies the desk and the evaluation protocol. **It does not yet report trading results.** Section VII contains the results template, the pre-registered metrics, and the rule that numbers enter this paper only from the Trade Journal, never from an agent's summary. Any earlier version presented as a "field report" without results should be read as this protocol.

### E. What This Paper Delivers

Section II specifies the desk architecture on the operator's agent system and the Binance MCP. Section III gives the strategy discovery loop and its statistical gates. Section IV covers market data, the Desk Signal, and execution through Binance. Section V covers sizing, risk limits and the kill switch. Section VI covers the paper-to-live ladder and daily operation. Section VII is the evaluation protocol and results template. Section VIII lists limitations.

---

## II. Desk Architecture

### A. The Primitive: An Agent With a Role, Tools, and Files

The desk runs on the operator's existing agent system. Each agent is defined by three things:

1. **A role file**: a short prompt naming the agent's single job, its inputs, its output format, and the documents it must read first.
2. **A tool allowlist**: the exact MCP tools the agent may call. Anything not listed is unavailable to that agent.
3. **Shared files**: the Desk Rules (read-only for every agent except through the operator) and the Trade Journal (append-only).

Agents do not rely on hidden model memory. All state that matters lives in files on disk that the operator can open, diff and audit. This replaces "persistent memory that cannot be inspected" with memory that can.

### B. The Decision Path

Let $x_t$ be the market state observed at time $t$: prices, volume, order-book depth, funding rates and news. Every action the desk takes is the output of a fixed chain of agents applied in order:

$$a_t = T\big(R\big(S\big(C\big(Sc(x_t)\big)\big)\big)\big) \tag{1}$$

where $Sc$ is the Scout, $C$ the Chart Analyst, $S$ the Strategist, $R$ the Risk Officer and $T$ the Trader. No agent may skip a stage. Any stage may return a null action, which ends the path, and the reason is written to the journal.

### C. How Much the Stages Protect: A Measured Bound

Let $f_i$ be the probability that stage $i$ passes an idea that later turns out to be bad. If the stages filtered *independently*, the probability that a bad idea reaches execution would be:

$$P(\text{bad trade executed}) = \prod_{i=1}^{k} f_i \tag{2}$$

Two corrections to how this equation is usually presented:

- **The $f_i$ are measured, not assumed.** Each $f_i$ is estimated from the journal: the fraction of ideas that passed stage $i$ and were later classified bad (negative out-of-sample result, or failed at a later gate). Until at least 50 ideas have passed through a stage, its $f_i$ is reported as "not yet measured", not as an illustrative number.
- **Independence is not guaranteed.** All stages read the same Binance data and often share the same model, so their errors are positively correlated. The product in (2) is therefore a *best case*. The worst case is $\min_i f_i$, where every bad idea that fools the weakest stage fools them all. The journal reports both numbers. The desk should be judged on the worst case until the measured joint pass rate shows otherwise.

This is also why the Risk Officer is built as code rather than as another model call (Section V.H): a deterministic check does not share the language model's failure modes, so it is the stage most likely to be independent of the others.

### D. Architecture Diagram

```
                         OPERATOR
                            |
                     CHIEF OF STAFF
                            |
   +---------+-----------+--------+----------+---------+
   |         |           |        |          |         |
 SCOUT -> CHART -> STRATEGIST -> RISK -> TRADER
   |      ANALYST                OFFICER     |
   |         |                     (code)    |
 News /   Binance MCP                      Binance MCP
 funding  (market data,                   (order tools,
          read-only)                    trading sub-account)
   |         |           |        |          |
   +---------+-----------+--------+----------+
                            |
                 TRADE JOURNAL (append-only)
```

*Fig. 1. Six-agent desk, decision path, and the two separate Binance MCP access levels.*

### E. Desk Rules as Shared State

Every agent reads one document before acting: the **Desk Rules**. It fixes:

- tradable symbols (for example, a fixed list of liquid USDT spot pairs) and the instruments the desk must never trade (margin, futures, leveraged tokens, newly listed pairs under 30 days old);
- capital, maximum risk per trade, maximum daily loss, maximum open positions, maximum holding period;
- the setup-score weights (Section III.B) and the gate thresholds (Table III);
- the cost model (Section III.C);
- two behavioural rules: every claim carries a source and a timestamp, and no agent may invent a price, a number or a result. A number with no journal or MCP source is treated as false.

The Desk Rules are versioned. Every journal entry records the Desk Rules version it was produced under, so a change of rules never silently mixes two regimes in one result.

### F. Routines and the 24-Hour Schedule

Autonomy comes from scheduled runs. A scheduler on the operator's system (cron, a hosted scheduled job, or the agent system's own routine feature) wakes the Chief of Staff on the schedule below, and the Chief of Staff dispatches the other agents. Because crypto trades 24/7, all times are **UTC** and "day" means the UTC calendar day.

**Table II. Agent routine schedule (UTC)**

| Agent | Schedule | Output |
|---|---|---|
| Scout | Every 30 min | Ranked findings with sources |
| Chart Analyst | Hourly, on candle close | Setups scored 7 or above |
| Strategist | 00:30 nightly | Tested trade plans |
| Risk Officer | On every plan; 00:15 daily review | APPROVED / VETO with reason |
| Trader | Every 15 min | Orders, fills, reconciliations |
| Chief of Staff | 07:00 and 19:00 daily; Sunday 18:00 | Briefs and weekly review |

**The schedule sets the desk's speed limit.** Signals are evaluated on hourly candle closes and orders are worked every 15 minutes, so a setup can wait up to about 75 minutes between candle close and order. The desk therefore only trades strategies whose expected edge lasts for hours to days. This is a design choice, not just a limitation, and the Strategist must reject any hypothesis whose holding period is shorter than 4 hours.

### G. The Handoff Protocol

Agents communicate in one fixed format:

```
TICKER:    BTCUSDT
IDEA:      Breakout above 20-bar high on 1h, trend stacked
EVIDENCE:  spot_klines 1h, close 2025-..-..T14:00Z; score 7.6 (components ...)
NEXT STEP: Strategist to backtest rule DS-v3 on 3y of 1h data
```

A fixed format removes ambiguity and makes every message machine-checkable. An idea without evidence goes back to its sender. A plan that reaches the Trader without an `APPROVED` stamp from the Risk Officer, matching the plan's ID and Desk Rules version, is refused.

### H. The Trade Journal

The desk writes every idea, score, plan, risk decision, simulated or real order, fill, and lesson to an append-only Trade Journal (one record per line, never edited in place). The journal is:

- the **audit trail**: any number in a brief must cite a journal record;
- the **calibration source**: the $f_i$ in (2), the win rate and payoff in (15), and the consistency test in (19) are all computed from it;
- the **graveyard**: rejected hypotheses are recorded with a fingerprint of their rule, so the same idea is never retested by accident, and every retest counts toward the trial count $N$ in Section III.E.

---

## III. The Strategy Discovery Loop

### A. From Signal to Hypothesis

Discovery starts with the Scout, which records every market event with a source and a timestamp: unusual 24-hour volume or price change from Binance tickers, extreme perpetual funding rates (read-only), and news. Each night the Strategist turns the day's findings into testable hypotheses. A hypothesis is admissible only if it names:

1. its **edge** (what it exploits),
2. the **reason** it should work,
3. the **counterparty** who loses when it does,
4. its **expected holding period**, which must be at least 4 hours (Section II.F).

Ideas without a stated edge are discarded before any computation is spent on them.

### B. The Setup Score

The Chart Analyst scores each candidate on five components computed from Binance candles: trend alignment, distance to a key level, momentum, relative volume, and catalyst strength. Each component $s_j$ is scored from 0 to 10 and combined with fixed weights $w_j$:

$$S = \sum_{j=1}^{5} w_j s_j, \qquad \sum_{j=1}^{5} w_j = 1, \qquad s_j \in [0, 10] \tag{3}$$

Only setups with $S \ge 7$ go to the Strategist. The weights live in the Desk Rules so the score cannot drift toward whatever the agent finds persuasive that day. The four chart-based components are computed by code from the candles; only "catalyst strength" is a model judgment, and it carries the smallest weight.

### C. Backtest Statistics and Costs

Every plan is backtested on at least three years of Binance history for its symbol, or the symbol's full history if shorter (and symbols under 2 years of history are not tradable). Gross results mean nothing without costs, so each of the $n$ round trips is charged a cost $c_{rt}$:

$$R_{net} = R_{gross} - n \cdot c_{rt} \tag{4}$$

For Binance spot, $c_{rt}$ is **two taker fees plus estimated spread and slippage**. The fee is taken from the account's actual fee tier (the base spot tier is 0.10% per side, lower with BNB discounts or VIP tiers; the Desk Rules record the tier in use). Slippage is estimated from order-book depth at the planned order size, not assumed to be zero.

Risk-adjusted performance is the Sharpe ratio of daily net returns $r$. **Crypto trades every day of the year, so the annualisation factor is $\sqrt{365}$, not the equity-market $\sqrt{252}$:**

$$SR_{ann} = \frac{\bar{r}}{\sigma_r}\sqrt{365} \tag{5}$$

Downside is measured by the maximum drawdown of the equity curve $V_t$:

$$MDD = \max_t \frac{P_t - V_t}{P_t}, \qquad P_t = \max_{s \le t} V_s \tag{6}$$

### D. Walk-Forward Validation

One backtest describes one path through history. The desk instead splits the data into rolling windows, fits the rules on each training window, and evaluates them untouched on the following test window. Rules are never adjusted after the test window has been seen. A strategy that works in only one window is classed as luck, not edge.

Because crypto regimes change fast, windows are short (for example, 12 months train, 3 months test, rolled forward by 3 months), and at least one test window must cover a drawdown of 30% or more in BTC, so that every surviving strategy has been tested through a crash.

### E. The Multiple-Testing Problem

An agent that tests ten ideas a night tests over three thousand a year, and some will look excellent by chance. Among $N$ independent strategies with no true edge, the best observed Sharpe ratio grows roughly as:

$$E[\max SR] \approx \sigma_{SR}\sqrt{2\ln N} \tag{7}$$

The desk evaluates every survivor with the **deflated Sharpe ratio** (Bailey and López de Prado), with two details that are commonly gotten wrong:

1. **Use the per-period Sharpe ratio, not the annualised one.** In (8), $\widehat{SR}$ is the Sharpe ratio of the *daily* returns (no $\sqrt{365}$), and $T$ is the number of daily observations. Mixing an annualised $SR$ with a daily $T$ overstates significance by a large factor.
2. **Count trials honestly.** $N$ is the number of *distinct* configurations tested for this family of strategies, taken from the journal's graveyard, including every failed variant and every retest. Highly similar variants can be grouped into an effective $N$, but the grouping method must be written down before testing.

The benchmark $SR_0$ is the expected maximum Sharpe under $N$ trials, using the exact form rather than (7):

$$SR_0 = \sqrt{V[\widehat{SR}_n]}\left((1-\gamma)\,\Phi^{-1}\!\left(1-\frac{1}{N}\right) + \gamma\,\Phi^{-1}\!\left(1-\frac{1}{Ne}\right)\right)$$

where $V[\widehat{SR}_n]$ is the variance of Sharpe ratios across the $N$ trials, $\gamma \approx 0.5772$ is the Euler–Mascheroni constant and $e$ is Euler's number. Then:

$$DSR = \Phi\!\left(\frac{(\widehat{SR} - SR_0)\sqrt{T-1}}{\sqrt{1 - \hat\gamma_3\widehat{SR} + \frac{\hat\gamma_4 - 1}{4}\widehat{SR}^2}}\right) \tag{8}$$

where $\hat\gamma_3$ is the skewness and $\hat\gamma_4$ the (non-excess) kurtosis of daily returns, and $\Phi$ is the standard normal distribution function. Crypto returns are heavily fat-tailed, so the kurtosis term matters and must never be dropped.

### F. The Gates

A strategy is called profitable in this paper only if it passes every gate in Table III. Failing any single gate sends it to the graveyard.

**Table III. Statistical gates for a profitable strategy**

| Gate | Threshold | Purpose |
|---|---|---|
| Trade count | $n \ge 30$ out-of-sample trades | Statistical meaning |
| Out-of-sample | $R_{net} > 0$ after full costs | Profit after costs |
| Deflated Sharpe | $DSR \ge 0.95$ with journal $N$ | Luck correction |
| Walk-forward | $\ge 70\%$ of windows positive | Stability |
| Crash window | Not worse than $-1.5\times$ planned max drawdown in the crash test window | Survives a crash |
| Latest year | Positive | Current regime |
| Parameter plateau | Neighbours within 20% still pass | No lucky setting |
| Noise test | 1-bar delay and $2\times$ slippage still positive | Real-world friction |
| Capacity | Planned size $\le 1\%$ of median hourly volume | Fill is realistic |

---

## IV. Market Data, Signals, and Execution Through Binance

### A. The Binance MCP as the Only Market Interface

The desk reaches the market through one channel: the Binance MCP server, which exposes the official Binance REST endpoints as tools. The desk uses two clearly separated groups of those tools.

- **Market data (read-only)**: candles (`spot_klines`), 24-hour tickers (`spot_ticker24hr`), prices (`spot_tickerPrice`), order-book depth (`spot_depth`), exchange rules (`spot_exchangeInfo`), and, read-only, perpetual funding data used as a sentiment input.
- **Account and orders**: balances (`spot_getAccount`), open orders and trade history (`spot_getOpenOrders`, `spot_getOrder`, `spot_myTrades`), and order placement and cancellation (`spot_newOrder`, `spot_deleteOrder`).

**The Binance MCP talks to the real exchange. There is no built-in paper mode.** Every order tool call is a real order. Paper trading is therefore implemented by the desk itself (Section IV.E), not by the broker.

### B. Tool Isolation by Agent

Order tools are the most dangerous capability in the system, so access is isolated by role (Table IV). Each agent's allowlist is set in its role file. A second, independent **deny list** in the agent system's permission settings blocks the dangerous tool families for every agent, including the Trader: all wallet transfer and withdrawal tools, all futures and margin tools, and all convert tools. If a role file is edited by mistake, the deny list still holds.

**Table IV. Tool access by agent**

| Agent | Market data | Account / orders |
|---|---|---|
| Scout | Tickers, funding (read), news source | None |
| Chart Analyst | Klines, depth | None |
| Strategist | Klines (history) | None |
| Risk Officer | Prices, depth, exchange rules | Account and open orders: **read only** |
| Trader | Prices, depth, exchange rules | `spot_newOrder`, `spot_deleteOrder`, order and account reads |
| Chief of Staff | Prices | Account: **read only** |
| *All agents* | — | **Denied:** wallet transfer / withdraw, futures, margin, convert |

### C. The Desk Signal

The Chart Analyst computes the Desk Signal in code from Binance candles (hourly by default). It combines four parts.

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
EXIT = (not bull) or close[t] < min(low[t-20 .. t-1]) or stop hit or target hit
```

### D. Exchange Rules Before Every Order

Binance rejects orders that break a symbol's filters. Before any order, simulated or real, the Trader reads `spot_exchangeInfo` for the symbol and:

- rounds quantity **down** to the lot step size,
- rounds price to the tick size,
- checks the order value is at or above the minimum notional; if the correctly sized order is below it, the trade is **skipped**, never sized up.

The paper simulator enforces the same filters, so a strategy cannot look good on paper with orders the exchange would refuse.

### E. Paper Mode Without a Broker Paper Account

Because the Binance MCP has no paper mode, the desk runs paper trading in three layers:

1. **Simulated fills.** In paper rungs, the Trader does not call `spot_newOrder`. It reads `spot_depth` at decision time, computes the fill price by walking the order book for the planned size, applies the real fee tier, and writes a simulated fill to the journal marked `mode: paper`.
2. **No order tools in paper rungs.** During paper rungs the Trader's allowlist does not include `spot_newOrder`. Paper mode is enforced by configuration, not by the Trader remembering it is in paper mode.
3. **An empty live account.** The trading sub-account (Section V.G) holds no funds until the strategy reaches rung 3. Even a mis-configured order call fails for insufficient balance.

---

## V. Position Sizing and Risk

### A. Fixed-Fractional Sizing

A strategy that passes every gate can still destroy an account if it is sized badly. The desk sizes every position so that the loss at the stop equals a fixed fraction $f$ of current equity $E$. For entry price $P_e$ and stop price $P_s$, the quantity is:

$$q = \frac{f \cdot E}{|P_e - P_s|} \tag{13}$$

then rounded down to the symbol's step size (Section IV.D). With $f = 1\%$ and $E = \$10{,}000$, a trade with a \$2 stop distance buys 50 units, and the worst planned loss is \$100 whatever the price of the asset, *before* fees and gap risk (Section V.E).

### B. Volatility Scaling

Stops are placed at a multiple $k$ of the average true range, so stop distance widens automatically when markets get more volatile. Substituting into (13):

$$q = \frac{f \cdot E}{k \cdot ATR_{14}} \tag{14}$$

Position size therefore shrinks as volatility rises, without any judgment call by the agents.

### C. Fractional Kelly as an Upper Bound

For a strategy with out-of-sample win rate $p$ and payoff ratio $b$ (average win divided by average loss), the Kelly fraction is:

$$f^* = p - \frac{1-p}{b} \tag{15}$$

Full Kelly is too aggressive for estimated inputs, so the desk uses a quarter of it, capped by the Desk Rules:

$$f = \min\left(\frac{f^*}{4},\, f_{max}\right) \tag{16}$$

For $p = 0.55$ and $b = 1.5$, $f^* = 0.25$ and quarter Kelly is 6.25%, but the 1% cap binds. Kelly is a ceiling, never a target. Its inputs come only from out-of-sample and paper trades in the journal, and only once there are at least 30 of them; before that, $f$ is set to half of $f_{max}$.

### D. Correlation and Portfolio Heat

Two positions in correlated assets are one bet sized twice. In crypto this is the normal case: most large coins move with BTC. With position weights $w$ and return covariance matrix $\Sigma$, portfolio volatility is:

$$\sigma_p = \sqrt{w^\top \Sigma\, w} \tag{17}$$

The Risk Officer treats any two positions with 30-day correlation above 0.7 as one bet, and limits total open risk, the portfolio heat $H$, across all $n$ positions:

$$H = \sum_{i=1}^{n} f_i \le H_{max} = 5\% \tag{18}$$

Correlations are recomputed daily, because crypto correlations jump toward 1 in sell-offs, which is exactly when the limit matters most.

### E. Event Risk

Crypto has no earnings dates, but it has scheduled events that cause gaps no stop can protect against. The Risk Officer blocks new positions in the 2 hours before and after:

- US central bank (FOMC) decisions and US CPI releases,
- large scheduled token unlocks for the symbol,
- announced Binance maintenance windows, and any delisting or monitoring-tag notice for the symbol (which also forces an exit).

It also flags every open position whose holding period crosses one of these events.

### F. The Kill Switch

Some failures require the desk to stop entirely. When any trigger in Table V fires, the Risk Officer cancels open orders, closes positions where specified, removes the Trader from the schedule, and alerts the operator.

**Table V. Kill switch triggers**

| Trigger | Threshold | Action |
|---|---|---|
| Daily loss | $\ge 2\%$ of equity in a UTC day | Flatten, pause desk |
| Losing streak | 3 losing trades in a row | Pause Trader |
| Stale data | No new closed candle for 2 bars | Cancel all orders |
| Slippage | Fill $> 0.5\%$ from plan | Pause, review |
| Reconciliation | Journal position $\ne$ exchange balance | Freeze desk |
| API errors | 3 consecutive order or auth errors | Freeze desk |
| Rule breach | Any agent acts outside the Desk Rules | Freeze desk |
| Strategy drift | Live result below Monte Carlo 5th percentile | Demote strategy |

### G. Layered Enforcement

Every limit is enforced in three independent layers:

1. **Desk Rules** read by every agent: a rule.
2. **Tool isolation**: the allowlist per agent plus the global deny list (Table IV), so only the Trader can reach order tools and nobody can reach withdrawals, futures, margin or convert.
3. **Exchange-side limits**, which are the actual wall:
   - The desk trades from a **dedicated Binance sub-account** funded only with the capital for the current rung. The worst possible loss is the sub-account balance.
   - The **API key** behind the Binance MCP has spot trading enabled and **withdrawals disabled**, with futures and margin permissions off and IP whitelisting on.
   - The operator moves capital *into* the sub-account by hand at each promotion. No agent can move it.

A prompt is a rule. A permission setting is a fence. An exchange setting is a wall.

### H. The Risk Officer Is Code

The Risk Officer's checks (sizing, heat, correlation, event windows, exchange filters, kill-switch triggers) are deterministic functions of the plan, the account state and the Desk Rules. A language model may write the human-readable reason, but **the approve/veto decision comes from code** and is the same every time for the same inputs. This keeps the veto from being talked around by a persuasive plan, and makes it the stage most independent of the others in (2).

---

## VI. From Paper to Live Capital

### A. The Promotion Ladder

Most automated systems fail at one transition: the move from simulated to real capital. The desk never makes that move in one step. Every strategy climbs the five rungs in Table VI, and a failure at any rung sends it back one rung.

**Table VI. The paper-to-live promotion ladder**

| Rung | Condition to advance | Capital | Order tools |
|---|---|---|---|
| 1. Paper | 2 weeks and $\ge 20$ trades, all logged | Simulated | Removed |
| 2. Match | Paper consistent with backtest, eq. (19) | Simulated | Removed |
| 3. Live tiny | Clean fills, no breaches, 20 trades | 10% of plan in sub-account | Enabled |
| 4. Approval gate | Operator approves each order | Planned size | Enabled |
| 5. Full rules | 4 weeks of clean journal | Planned size | Enabled |

### B. The Consistency Test

Rung 2 asks whether paper results agree with the backtest. With $n$ paper trades of mean return $\bar r_p$, and a backtest mean $\bar r_b$ with per-trade standard deviation $\sigma_b$, the desk computes:

$$z = \frac{\bar r_p - \bar r_b}{\sigma_b / \sqrt{n}} \tag{19}$$

A strategy advances only when $n \ge 20$ and $z > -2$. A strongly negative $z$ means live conditions are destroying the edge the backtest promised, usually through costs, latency or a changed regime, and the strategy goes back to the Strategist. With $n$ as small as 20 this test can only catch large failures. It is a minimum check, not proof of consistency, and rungs 3 to 5 exist to keep testing with real fills.

### C. Daily Operation

The operator's day comes down to two messages and a set of approvals. The morning brief summarises the market, the ranked findings and surviving setups, and lists every veto with its reason. Live orders at rung 4 arrive as a fixed card: symbol, side, entry, stop, target, size, risk, and rationale, each with its journal reference. **Silence is treated as rejection.** Each Sunday the Chief of Staff compares live results with backtests, demotes decaying strategies, and archives failed ideas in the graveyard so they are never retested.

---

## VII. Evaluation Protocol and Results Template

This section is fixed **before** results are collected. Results enter it only from the Trade Journal, and the Chief of Staff may not write a number here that it cannot cite to a journal record.

### A. Pre-Registered Metrics

For every strategy that reaches rung 2 or above, report:

**Table VII. Strategy results (to be filled from the journal)**

| Strategy | Symbol / TF | Trials $N$ | OOS trades | $R_{net}$ | $SR_{ann}$ | $DSR$ | MDD | WF windows + | Rung |
|---|---|---|---|---|---|---|---|---|---|
| — | — | — | — | — | — | — | — | — | — |

For the desk as a whole, report:

**Table VIII. Desk funnel (to be filled from the journal)**

| Stage | Ideas in | Passed | Later classified bad | Measured $f_i$ |
|---|---|---|---|---|
| Scout → Chart Analyst | — | — | — | — |
| Chart Analyst → Strategist | — | — | — | — |
| Strategist (gates) | — | — | — | — |
| Risk Officer | — | — | — | — |
| **Joint (measured)** | — | — | — | — |
| Product bound, eq. (2) | | | | — |
| Worst case $\min f_i$ | | | | — |

### B. Required Figures

1. Out-of-sample equity curve per strategy, with walk-forward window boundaries marked.
2. Paper vs. backtest per-trade return distribution (the input to (19)).
3. Live fill price vs. planned price (slippage) per trade.

### C. Reporting Rules

- Report **every** strategy that reached rung 2, including failures, not only the winners.
- Report the total number of hypotheses tested in the period, not only those that passed.
- Report results net of actual fees paid, taken from `spot_myTrades`, not from the cost model.
- If no strategy has passed all gates yet, say so. "No edge found in $N$ trials" is a valid and useful result.

---

## VIII. Limitations

- **Not a high-frequency system.** Routines run minutes apart and signals use hourly closes (Section II.F), so edges that decay within minutes are out of reach by design.
- **Spot long-only.** Futures, margin and shorting are denied. The desk cannot profit from falling markets except by staying flat.
- **No broker paper mode.** Paper fills are simulated from order-book snapshots. They miss queue position, partial fills and market impact beyond the visible book. Rungs 3 to 5 exist to catch what simulation misses.
- **Model routing and determinism.** The language models behind the agents can change between runs. This is why every decision that matters for capital (gates, sizing, veto, kill switch) is code, and why the models only propose and explain.
- **Exchange and connector risk.** The desk depends on Binance being available and the MCP server staying connected. A disconnected connector triggers the stale-data and API-error kill switches; it never results in "trading blind".
- **Regulatory and account restrictions.** Binance availability and permitted products vary by country. The operator is responsible for using an account and products that are legal for them.
- **Statistics are not guarantees.** The gates reduce the chance of trading on luck. They cannot guarantee future profit, and no architecture can give edge to a strategy that has none.

---

## IX. Conclusion

A hedge fund has never been defined by its building or its headcount. It is defined by a process: research, testing, risk control and disciplined execution, running without interruption. This paper shows how that process can be assigned to a team of role-isolated agents on an existing agent system, with the Binance MCP as the only route to the market, deterministic code holding the veto, and the exchange account itself as the final wall. Each agent has one job, one schedule and one record, and the operator keeps authority over capital. The edge still has to be found. The difference is that the search no longer stops when the operator does, and every claim the desk makes can be traced back to the journal.

---

## References

1. Model Context Protocol, "Specification," modelcontextprotocol.io, 2025.
2. Binance, "Spot API documentation" and "Binance MCP server," Binance Developers, 2026.
3. D. H. Bailey and M. López de Prado, "The deflated Sharpe ratio: Correcting for selection bias, backtest overfitting, and non-normality," *J. Portfolio Management*, vol. 40, no. 5, pp. 94–107, 2014.
4. D. H. Bailey, J. M. Borwein, M. López de Prado, and Q. J. Zhu, "Pseudo-mathematics and financial charlatanism: The effects of backtest overfitting on out-of-sample performance," *Notices of the AMS*, vol. 61, no. 5, pp. 458–471, 2014.
5. M. López de Prado, *Advances in Financial Machine Learning*. Hoboken, NJ, USA: Wiley, 2018.
6. J. L. Kelly, Jr., "A new interpretation of information rate," *Bell System Technical Journal*, vol. 35, no. 4, pp. 917–926, 1956.
7. W. F. Sharpe, "The Sharpe ratio," *J. Portfolio Management*, vol. 21, no. 1, pp. 49–58, 1994.
8. J. W. Wilder, *New Concepts in Technical Trading Systems*. Greensboro, NC, USA: Trend Research, 1978.
9. R. Pardo, *The Evaluation and Optimization of Trading Strategies*, 2nd ed. Hoboken, NJ, USA: Wiley, 2008.
