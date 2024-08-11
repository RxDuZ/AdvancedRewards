# AdvancedRewards

**AdvancedRewards is a PocketMine-MP plugin to add rewards to your Factions server.**

<p align="center"><img src="img/reward.png"></p>

## Prerequisites

- InvMenu virion
- PMMP 5.17.0+

### Implementations

- [x] Categories
- [x] Multiple rewards
- [x] Custom permissions
- [x] Custom messages
- [x] Sounds
- [x] Configurable command

---

### 💾 Config

```yml
#     ▄████████ ████████▄   ▄█    █▄     ▄████████ ███▄▄▄▄    ▄████████    ▄████████ ████████▄     ▄████████    ▄████████  ▄█     █▄     ▄████████    ▄████████ ████████▄     ▄████████
#    ███    ███ ███   ▀███ ███    ███   ███    ███ ███▀▀▀██▄ ███    ███   ███    ███ ███   ▀███   ███    ███   ███    ███ ███     ███   ███    ███   ███    ███ ███   ▀███   ███    ███
#    ███    ███ ███    ███ ███    ███   ███    ███ ███   ███ ███    █▀    ███    █▀  ███    ███   ███    ███   ███    █▀  ███     ███   ███    ███   ███    ███ ███    ███   ███    █▀
#    ███    ███ ███    ███ ███    ███   ███    ███ ███   ███ ███         ▄███▄▄▄     ███    ███  ▄███▄▄▄▄██▀  ▄███▄▄▄     ███     ███   ███    ███  ▄███▄▄▄▄██▀ ███    ███   ███
#  ▀███████████ ███    ███ ███    ███ ▀███████████ ███   ███ ███        ▀▀███▀▀▀     ███    ███ ▀▀███▀▀▀▀▀   ▀▀███▀▀▀     ███     ███ ▀███████████ ▀▀███▀▀▀▀▀   ███    ███ ▀███████████
#    ███    ███ ███    ███ ███    ███   ███    ███ ███   ███ ███    █▄    ███    █▄  ███    ███ ▀███████████   ███    █▄  ███     ███   ███    ███ ▀███████████ ███    ███          ███
#    ███    ███ ███   ▄███ ███    ███   ███    ███ ███   ███ ███    ███   ███    ███ ███   ▄███   ███    ███   ███    ███ ███ ▄█▄ ███   ███    ███   ███    ███ ███   ▄███    ▄█    ███
#    ███    █▀  ████████▀   ▀██████▀    ███    █▀   ▀█   █▀  ████████▀    ██████████ ████████▀    ███    ███   ██████████  ▀███▀███▀    ███    █▀    ███    ███ ████████▀   ▄████████▀
#                                                                                                 ███    ███                                         ███    ███
# AdvancedRewards made by iRxDuZ

# Do not touch :)
CONFIG_VERSION: 1

# Find sounds here: https://www.digminecraft.com/lists/sound_list_pe.php
# Sound when claiming a reward
reward-claim-sound: "random.levelup"

# Sound when failing to claim a reward
reward-failed-sound: "note.bassattack"

# Broadcast message to claim rewards
# Custom message in /messages.yml
broadcast-claim-rewards: true

# Reward command configuration
command:
  name: "reward"
  description: "Claim your rewards"
  aliases: ["reclaim", "daily"]
```

### 💾 Categories

```yml
user:
  customname: "&l&aUser"
  description: "&aRewards for all users"
  slot: 0
  representative-item: "paper"
premium:
  customname: "&l&bPremium"
  description: "&aRewards for premium users"
  slot: 2
  representative-item: "paper"
```

### 💾 Rewards

```yml
daily:
  customname: "&gDaily Reward"
  description: "&eclaim every day\n&r{STATUS}"
  category: user
  slot: 0
  representative-item: "emerald"
  commands:
    - "give {PLAYER} diamond_sword 1"
  cooldown: 86400
  permission: "reward.daily.claim"
weekly:
  customname: "&gWeekly Reward"
  description: "&eClaim every week\n&r{STATUS}"
  category: user
  slot: 2
  representative-item: "emerald"
  commands:
    - "give {PLAYER} golden_apple 16"
  cooldown: 604800
  permission: "reward.weekly.claim"
monthly:
  customname: "&gMonthly Reward"
  description: "&eClaim every month\n&r{STATUS}"
  category: user
  slot: 4
  representative-item: "emerald"
  commands:
    - "give {PLAYER} diamond 64"
  cooldown: 2592000
  permission: "reward.monthly.claim"
vip:
  customname: "&gVip Reward"
  description: "&eAvailable every day for premium users\n&r{STATUS}"
  category: premium
  slot: 0
  representative-item: "diamond"
  commands:
    - "give {PLAYER} diamond 64"
    - "give {PLAYER} emerald 64"
    - "givemoney {PLAYER} 10000"
  cooldown: 86400
  permission: "reward.vip.claim"
```

## Permissions

| Permissions          | Description                  | Default |
| -------------------- | ---------------------------- | ------- |
| `reward.command.use` | Allow to use /reward command | `true`  |

### ✔ Credits

| Authors | Github                              | Lib                                          |
| ------- | ----------------------------------- | -------------------------------------------- |
| Muqsit  | [Muqsit](https://github.com/Muqsit) | [InvMenu](https://github.com/Muqsit/InvMenu) |
