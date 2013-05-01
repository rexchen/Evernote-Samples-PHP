Opauth/Evernote
================
Opauth strategy for Evernote authentication.

Getting started
----------------
1. Install Opauth-Evernote:
   ```bash
   cd path_to_opauth/Strategy
   git clone git://github.com/evernote/opauth-evernote.git Evernote
   ```
2. Create a Evernote API Key at http://dev.evernote.com/
3. Configure Opauth-Evernote strategy.
4. Direct user to `http://path_to_opauth/evernote` to authenticate


Strategy configuration
----------------------

Required parameters:

```php
<?php
'Evernote' => array(
  'client_id' => 'YOUR CLIENT ID',
  'client_secret' => 'YOUR CLIENT SECRET',
  'sandbox' => [TRUE or FALSE]
)
```

References
----------
- [Evernote Developer Site](http://dev.evernote.com/)

