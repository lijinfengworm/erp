#coding:utf-8
try:
    import apport_python_hook
except ImportError:
    pass
else:
    apport_python_hook.install()
import sys
sys.setdefaultencoding('utf-8')
