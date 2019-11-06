<?hh
/**
 * Copyright (c) 2015-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree. An additional grant
 * of patent rights can be found in the PATENTS file in the same directory.
 */


class :x:js-scope extends :x:element implements XHPAwaitable {
  use XHPAsync;

  protected async function asyncRender(): Awaitable<XHPRoot> {
    $calls = new JSCalls();
    $instances = new JSInstances();
    $this->setContext(':x:js-scope/calls', $calls);
    $this->setContext(':x:js-scope/instances', $instances);

    $child_waithandles = vec [ ];
    foreach ($this->getChildren() as $child) {
      if ($child is :x:composable-element) {
        $child->__transferContext($this->getAllContexts());
        $child_waithandles[] = (async () ==> await $child->__flushSubtree())();
      } else {
        invariant_violation(
          '%s is not an :x:composable-element',
          is_object($child) ? get_class($child) : gettype($child),
        );
      }
    }
    $children = await HH\Asio\v($child_waithandles);
    $this->replaceChildren();

    return
      <x:frag>
        {$children}
        <script>
          var XHPJS = window.XHPJS ? window.XHPJS : require('xhpjs');
          new XHPJS(
            {$instances->toJson()},
            {$calls->toJson()}
          );
        </script>
      </x:frag>;
  }
}
