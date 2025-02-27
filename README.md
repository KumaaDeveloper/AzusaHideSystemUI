## AzusaHideSystemUI
AzusaHideSystemUI is a PocketMine plugin that allows players to hide other players‘ nametags and hide other players’ skins.

## Features
- Can hide other players' nametags 
- Can hide other players' skins

## Commands
Commands | Default | Permission
--- | --- | ---
`/hidesystem` or `/hs` | Op | hidesystem.command

## Configuration
```yaml
# AzusaHideSystemUI Configuration

messages:

# Success in hiding Nametags
  hide_nametag_success: "§aNametag has been hidden."
  remove_nametag_success: "§aNametag has been re-displayed."

# Success in hiding the Nametag
  hide_player_success: "§aThe player has been hidden."
  remove_player_success: "§aThe player has been re-displayed."

# Message when the nametag is already hidden/visible
  already_hidden_nametag: "§cNametag is already hidden."
  already_visible_nametag: "§cNametag is already visible."

# Message when the player is already hidden/visible
  already_hidden_player: "§cThe player is already hidden."
  already_visible_player: "§cThe player is already in the visible state."

forms:
  main_form:
    title: "AzusaHideSystem UI"
    content: "Please select the action you want to perform."
    buttons:
      hide_nametag: "Hide NameTag"
      hide_player: "Hide Player"
  hidename_tag_form:
    title: "AzusaHideSystem UI"
    content: "Please select the action you want to perform for the nametag."
    buttons:
      hide: "Hide NameTag"
      remove: "Unhide NameTag"
  hide_player_form:
    title: "AzusaHideSystem UI"
    content: "Please select the action you want to perform for the player."
    buttons:
      hide: "Hide Player"
      remove: "UnHide Player"
```

## Bug And Issues
- Fix config
- Fix message
- Fix Form
- Fix Hide Nametag
- Fix Hide Player
- Fix Playeronjoin
