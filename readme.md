Remote friend support for Elgg
==============================

This is a plugin that provides Idno/Known/Indieweb remote friending support to Elgg.

This plugin provides a bookmarklet for adding friends who are not on your Elgg network (and not 
necessarily even running Elgg themselves) to your friends list.

It works by looking for Microformats2 data, and so is basically an Elgg port of a similar mechanism
I wrote for Known.

What can I do with it?
----------------------

You can add friends from other sites to your friend list.

At the moment, with this plugin, that's about all you can do. However these users can add as placeholders for other 
plugins - for example, you could harvest their public key for PGP signon (http://www.marcus-povey.co.uk/2014/05/29/friend-only-posts-and-openpgp-sign-in-on-a-distributed-social-network/) 
which I may get around to writing.

It uses Barnaby Walter's MF2 parsing library, so be sure to git clone --recursive

See
---
 * Author: Marcus Povey <http://www.marcus-povey.co.uk>
 * PHP MF2 parser <https://github.com/indieweb/php-mf2>
 * Elgg MF2 support <https://github.com/mapkyca/elgg-mf2> (recommended)


